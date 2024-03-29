<?php
/**
 * @package     Console
 *
 * @subpackage  CreateMigration
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

use console\components\Command;
use console\components\InputArgument;
use console\components\Output;

/**
 * Migration class
 */
class CreateMigration extends Command
{
    /**
     * @var string $name The name of the command
     */
    protected $name        = 'migration:create';
    /**
     * @var string $help The help of the command
     */
    protected $help        = 'Create a new migration file';
    /**
     * @var string $description The description of the command
     */
    protected $description = 'Create a new migration file';

    /**
     * Constructor for the CreateMigration command.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the migration');
        $this->addArgument('module', InputArgument::OPTIONAL, 'The name of the module');
        $this->addArgument('template', InputArgument::OPTIONAL, 'The template to use');
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
        $template      = $this->input->getArgument('template');
        $path          = app_path(($module ? 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR : '') . 'migrations');
        $migrationName = sprintf('m%s_%s', date('ymd_His'), $name);

        $migrationFile = $path . DIRECTORY_SEPARATOR . $migrationName . '.php';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if (!empty($template)) {
            $templateFile = resources_path('templates/migration/' . $template . '.php');
        } else {
            $templateFile = resources_path('templates/migration/basic.php');
        }

        if (!file_exists($templateFile)) {
            $this->output->setDecorated(Output::TEXT_COLOR_RED);
            $this->output->writeln('Migration template file not found.');
            exit(1);
        }

        $migrationTemplate = file_get_contents($templateFile);

        $replacements = [
            '{{migration_name}}'  => $migrationName,
            '{{table_name}}'      => $name,
            '{{namespace}}'       => $module ? 'modules\\' . $module . '\\migrations' : 'app\migrations',
            '{{date}}'            => date('Y-m-d'),
            '{{year}}'            => date('Y'),
            '{{app_name}}'        => env('APP_NAME') ?? '',
            '{{developer_name}}'  => env('APP_DEVELOPER_NAME') ?? '',
            '{{developer_email}}' => env('APP_DEVELOPER_EMAIL') ?? '',
        ];

        foreach ($replacements as $placeholder => $replacement) {
            $migrationTemplate = str_replace($placeholder, $replacement, $migrationTemplate);
        }

        if (file_put_contents($migrationFile, $migrationTemplate) === false) {
            $this->output->setDecorated(Output::TEXT_COLOR_RED);
            $this->output->writeln('Failed to create migration file.');
            exit(1);
        }

        $this->output->setDecorated(Output::TEXT_COLOR_GREEN);
        $this->output->writeln($migrationName . ' created successfully.');
        $this->output->writeln('@location: ' . $path);
        exit(0);
    }
}
