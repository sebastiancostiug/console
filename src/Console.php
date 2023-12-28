<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  Console
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    slim-api-skeleton
 * @see
 *
 * @since       2023-12-01
 *
 */

namespace seb\console;

use seb\bootstrap\foundation\App;
use seb\common\Collection;
use seb\console\components\Command;
use seb\console\components\Input;
use seb\console\components\InputArgument;
use seb\console\components\Output;
use seb\console\interfaces\InputInterface;
use seb\console\interfaces\OutputInterface;

/**
 * Class Console
 *
 * Represents a console application that can execute commands.
 */
class Console
{
    /**
     * @var App $app The Slim application instance.
     */
    protected static $app;
    /**
     * The array of registered commands.
     *
     * @var array
     */
    protected static $commands = [];

    /**
     * @var \Closure $handler The handler for the console.
     */
    protected \Closure $handler;

    /**
     * @var InputInterface $input The input interface.
     */
    protected InputInterface $input;

    /**
     * @var OutputInterface $output The output interface.
     */
    protected Output $output;

    /**
     * Sets up the application instance.
     *
     * @param App $app The Slim application instance.
     * @return void
     */
    public static function setup(App &$app)
    {
        self::$app = $app;
    }

    /**
     * Returns the application instance.
     *
     * @return App The Slim application instance.
     */
    public static function app(): App
    {
        return self::$app;
    }

    /**
     * Returns a new instance of the Console class.
     *
     * @return self
     */
    public static function console(): self
    {
        return new static;
    }

    /**
     * Returns a collection of commands.
     *
     * @return Collection The collection of commands.
     */
    public static function commands(): Collection
    {
        return new Collection(self::$commands);
    }

    /**
     * Creates a new command with the given signature and handler.
     *
     * @param string   $signature The command signature.
     * @param \Closure $handler   The command handler.
     *
     * @return Command The created command.
     */
    public static function command($signature, \Closure $handler)
    {
        $command = new Command;

        $command->setHandler($handler);

        $input = explode(' ', $signature);

        $name = array_shift($input);
        $command->setName($name);

        $setName = fn($arg) => str_between($arg, '{', '}');
        $addArg  = fn($arg) => $command->addArgument($setName($arg), InputArgument::REQUIRED);

        $arguments = new Collection($input);
        $arguments = $arguments->each($addArg);

        static::$commands[$name] = $command;

        return $command;
    }

    /**
     * Sets the handler for the console.
     *
     * @param \Closure $handler The handler function to be set.
     *
     * @return $this
     */
    public function setHandler(\Closure $handler)
    {
        $this->handler = $handler->bindTo($this, $this);

        return $this;
    }

    /**
     * Magic method to handle dynamic method calls.
     *
     * @param string $method    The name of the method being called.
     * @param array  $arguments The arguments passed to the method.
     *
     * @return void
     * @throws Exception When the method does not exist.
     */
    public function __call($method, array $arguments)
    {
        throw_when($method !== 'handler', "Method {$method} does not exist.");

        call_user_func($this->handler, $arguments);
    }

    /**
     * Executes the console command.
     *
     * @return integer The exit code.
     * @throws Exception If no handler was set for the console.
     */
    public function execute()
    {

        try {
            $this->handler();
        } catch (\Exception $e) {
        }

        return 0;
    }

    /**
     * Runs the application.
     *
     * Retrieves the command name from the command line arguments and executes the corresponding command.
     * If the command is not found, an error message is displayed.
     *
     * @return mixed
     */
    public function run()
    {
        $commandName = $_SERVER['argv'][1] ?? null;

        if (!$commandName || !isset(static::$commands[$commandName])) {
            self::all();

            return 0;
        }

        $arguments = array_slice($_SERVER['argv'], 2);

        $command = static::$commands[$commandName];

        $arguments = $this->setArguments($command, $arguments);

        $command->setInput(new Input($arguments));

        $command->execute();
    }

    /**
     * Set the arguments for a command.
     *
     * @param Command $command   The command object.
     * @param array   $arguments The array of arguments.
     *
     * @return array The associated arguments.
     */
    public function setArguments(Command $command, array $arguments)
    {
        $associatedArguments = [];

        foreach ($command->getArguments()->toArray() as $argument) {
            $name = $argument->getName();
            if ($argument->isRequired() && empty($arguments)) {
                $output = new Output;

                $output->writeln("Required argument $name is missing.");

                exit(1);
            }
            $associatedArguments[$name] = array_shift($arguments);
        }

        return $associatedArguments;
    }

    /**
     * Adds a command to the application.
     *
     * @param Command $command The command to add.
     *
     * @return void
     */
    public function add(Command $command)
    {
        self::$commands[$command->getName()] = $command;
    }

    /**
     * Finds a command by its name.
     *
     * @param string $name The name of the command.
     *
     * @return self|null The command object if found, null otherwise.
     */
    public function find(string $name): ?self
    {
        return $this->commands[$name] ?? null;
    }

    /**
     * Checks if a command exists.
     *
     * @param string $name The name of the command.
     *
     * @return boolean True if the command exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * Returns all the registered commands.
     *
     * @return Echoes the formatted list of commands.
     */
    public function all(): Collection
    {
        $output = new Output;
        $output->writeln('Available commands:');
        $output->writeln('------------------');

        // Find the maximum lengths
        $maxNameLength = static::commands()->max(fn ($command) => strlen($command->getName()));
        $maxArgsLength = static::commands()->max(fn ($command) => strlen('{' . implode('} {', $command->getArguments()->keys()) . '}'));

        return static::commands()->map(function ($command) use ($output, $maxNameLength, $maxArgsLength) {
            $output->setDecorated(Output::TEXT_COLOR_GREEN);

            $name = str_pad($command->getName(), $maxNameLength + 1);
            $output->write($name);

            $output->setDecorated(Output::TEXT_COLOR_YELLOW);
            $args = str_pad('{' . implode('} {', $command->getArguments()->keys()) . '}', $maxArgsLength + 1);
            $output->write($args);

            $output->setDecorated(Output::TEXT_COLOR_WHITE);
            $output->writeln($command->getDescription());

            return;
        });
    }
}
