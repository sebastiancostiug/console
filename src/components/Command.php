<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  Command
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

namespace seb\console\components;

use seb\console\Console;
use seb\common\Collection;

/**
 * Class Command
 *
 * Represents a command in the console application.
 */
class Command extends Console
{
    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'command:add-signature';

    /**
     * The help information for the command.
     *
     * @var string
     */
    protected $help = 'Add command help info';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add command description information';

    /**
     * The array of command arguments.
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * __construct
     *
     * @return void
     */
    protected function __construct()
    {
        $this->output = new Output();
    }

    /**
     * Define a required command argument.
     *
     * @param string $description The description of the argument.
     *
     * @return array The argument definition.
     */
    protected function require($description = '')
    {
        return [InputArgument::REQUIRED, $description];
    }

    /**
     * Define an array command argument.
     *
     * @param string $description The description of the argument.
     * @param array  $default     The default value of the argument.
     *
     * @return array The argument definition.
     */
    protected function array($description = '', array $default = [])
    {
        return [InputArgument::IS_ARRAY, $description, $default];
    }

    /**
     * Define an optional command argument.
     *
     * @param string $description The description of the argument.
     * @param mixed  $default     The default value of the argument.
     *
     * @return array The argument definition.
     */
    protected function optional($description = '', mixed $default = false)
    {
        return $default ? [InputArgument::OPTIONAL, $description, $default] : [InputArgument::OPTIONAL, $description];
    }

    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName($this->name)
            ->setHelp($this->help)
            ->setDescription($this->description);

        $arguments = new Collection($this->arguments());
        $arguments->each(
            fn ($options, $name) => $this->addArgument($name, ...$options)
        );
    }

    /**
     * Get the name of the command.
     *
     * @return string The name of the command.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the console.
     *
     * @param string $name The name of the console.
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the help message for the console command.
     *
     * @param string $help The help message to set.
     *
     * @return self
     */
    public function setHelp(string $help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * Set the description of the console.
     *
     * @param string $description The description of the console.
     *
     * @return self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the description of the command.
     *
     * @return string The description of the command.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the command arguments.
     *
     * @return Collection The command arguments.
     */
    public function getArguments()
    {
        return new Collection($this->arguments);
    }

    /**
     * Retrieves the value of the specified argument.
     *
     * @param string $name The name of the argument.
     *
     * @return mixed The value of the argument.
     */
    public function getArgument(string $name)
    {
        return $this->arguments[$name];
    }

    /**
     * Adds an argument to the console command.
     *
     * @param string  $name The name of the argument.
     * @param integer $mode The mode of the argument (optional). Defaults to InputArgument::OPTIONAL.
     *
     * @return void
     */
    public function addArgument(string $name, int $mode = InputArgument::REQUIRED)
    {
        $this->arguments[$name] = new InputArgument($name, $mode);
    }

    /**
     * Sets the input for the command.
     *
     * @param Input $input The input object.
     *
     * @return void
     */
    public function setInput(Input $input)
    {
        $this->input = $input;
    }
}
