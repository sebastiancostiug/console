<?php
/**
 * @package     Console
 *
 * @subpackage  CleanVendor
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
 * CleanVendor class
 */
class CleanVendor extends Command
{
    /**
     * @var string $name The name of the command
     */
    protected $name        = 'service:clean-vendor';
    /**
     * @var string $help The help of the command
     */
    protected $help        = 'Clean vendor folder';
    /**
     * @var string $description The description of the command
     */
    protected $description = 'Clean vendor folder. Dry run shows what would be deleted without actually deleting it.';

    /**
     * Constructor for the CleanVendor command.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addArgument('dryrun', InputArgument::OPTIONAL, 'Dry run or not.');
    }

    /**
     * handler
     *
     * @return void
     */
    public function handler()
    {
        $dryrun = $this->input->getArgument('dryrun');
        $dryrun = $dryrun === 'true' || $dryrun === '1' || $dryrun === 'yes' || $dryrun === 'y' || $dryrun === 'on';

        if ($dryrun) {
            $this->output->setDecorated(Output::TEXT_COLOR_GREEN);
            $this->output->writeln('Dry run enabled. No files will be deleted.');
        }
        $this->output->setDecorated(Output::TEXT_COLOR_RED);
        $this->output->writeln('Cleaning vendor folder...');

        $this->output->writeln('Removing folders...');
        $result1 = remove_dirs(
            vendor_path(),
            [
                '.git',
                '.github',
                'docs',
                'examples',
                'tests',
                'test',
            ],
            $dryrun
        );

        extract($result1);

        $this->output->writeln('Removing files...');
        $result2 = remove_files(
            vendor_path(),
            [
                '.gitignore',
                '.gitattributes',
                '.editorconfig',
                '.php_cs',
            ],
            $dryrun
        );

        $files += $result2['files'];
        $bytes += $result2['bytes'];

        $this->output->setDecorated(Output::TEXT_COLOR_GREEN);
        $this->output->writeln('Done.');
        $this->output->writeln('Removed ' . $files . ' files.');
        $this->output->writeln('Freed ' . $bytes . ' bytes.');
        exit(0);
    }
}
