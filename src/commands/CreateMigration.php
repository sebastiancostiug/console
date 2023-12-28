<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  CreateMigration
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    slim-api-skeleton
 * @see
 *
 * @since       2023-12-27
 *
 */

namespace seb\console\commands;

use seb\console\components\Command;
use seb\console\components\InputArgument;
use seb\console\components\Output;

/**
 * Migration class
 */
class CreateMigration extends Command
{
    protected $name        = 'migration:create';
    protected $help        = 'Create a new migration file';
    protected $description = 'Create a new migration file';

    public function __construct()
    {
        parent::__construct();

        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the migration');
        $this->addArgument('module', InputArgument::OPTIONAL, 'The name of the module');
    }

    /**
     * handler
     *
     * @return void
     */
    public function handler()
    {
        $name          = $this->input->getArgument('name');
        $module        = $this->input->getArgument('module');
        $path          = app_path(($module ? 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR : '') . 'migrations');
        $migrationName = sprintf('m%s_%s', date('ymd_His'), $name);

        $migrationFile = $path . DIRECTORY_SEPARATOR . $migrationName . '.php';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $migrationTemplate = file_get_contents(core_path('database/MigrationTemplate.php'));

        $replacements = [
            '{{migration_name}}'  => $migrationName,
            '{{table_name}}'      => $name,
            '{{namespace}}'       => $module ? 'modules\\' . $module . '\\migrations' : 'app\migrations',
            '{{date}}'            => date('Y-m-d'),
            '{{year}}'            => date('Y'),
            '{{app_name}}'        => config('app.name') ?? '',
            '{{developer_name}}'  => config('app.developer_name') ?? '',
            '{{developer_email}}' => config('app.developer_email') ?? '',
        ];

        foreach ($replacements as $placeholder => $replacement) {
            $migrationTemplate = str_replace($placeholder, $replacement, $migrationTemplate);
        }

        $output = new Output;

        if (file_put_contents($migrationFile, $migrationTemplate) === false) {
            $output->setDecorated(Output::TEXT_COLOR_RED);
            $output->writeln('Failed to create migration file.');
            exit(1);
        }

        $output->setDecorated(Output::TEXT_COLOR_GREEN);
        $output->writeln($migrationName . ' created successfully.');
        $output->writeln('@location: ' . $path);
        exit(0);
    }
}
