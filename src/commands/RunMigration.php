<?php
/**
 * @package     Console
 *
 * @subpackage  RunMigration
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    commands
 *
 * @since       2023-12-27
 */

namespace console\commands;

use common\Collection;
use console\components\Command;
use console\components\InputArgument;

/**
 * Migration class
 */
class RunMigration extends Command
{
    /**
     * @var string $name The name of the command
     */
    protected $name        = 'migration:run';
    /**
     * @var string $help The help of the command
     */
    protected $help        = 'Run migrations';
    /**
     * @var string $description The description of the command
     */
    protected $description = 'Run migrations';

    /**
     * Constructor for the CreateMigration command.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addArgument('direction', InputArgument::OPTIONAL, 'The direction of the migration');
    }

    /**
     * handle
     *
     * @return void
     */
    public function handler()
    {
        $direction = $this->input->getArgument('direction') ?? 'up';

        $folder = scandir(migrations_path());
        $migrationFiles = array_slice($folder, 2, count($folder));

        $migrations = array_map(fn ($migration) => migrations_path() . $migration, $migrationFiles);

        $errors = [];
        $run = array_map(function ($migration) use (&$errors) {
            require_once $migration;
            $migrationClass = class_basename(str_before($migration, '.php'));

            try {
                return new $migrationClass();
            } catch (\Exception $e) {
                $errors[] = [
                    'file' => $migrationClass,
                    'error' => $e->getMessage(),
                ];
            }
        }, $migrations);

        if (count($errors) > 0) {
            $errors = new Collection($errors);

            $errorsOutput = $errors->map(function ($error) {
                return $error['file'] . ': ' . $error['error'];
            })->implode(PHP_EOL);

            $this->output->writeln($errors);
            $this->output->writeln($errorsOutput);
            exit(1);
        }

        try {
            array_walk($run, fn ($migration) => $migration->{$direction}());
            $this->output->writeln('All new migrations ran, check the database for changes.');
            exit(0);
        } catch (\Throwable $th) {
            $this->output->writeln($th->getMessage());
            exit(1);
        }
    }
}
