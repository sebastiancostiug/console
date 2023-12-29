<?php
/**
 * @package     Core
 *
 * @subpackage  CleanVendor
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    commands
 * @see
 *
 * @since       2023-12-27
 */

namespace console\commands;

use console\components\Command;
use console\components\InputArgument;
use console\components\Output;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
        $result1 = $this->removeDirs([
            '.git',
            '.github',
            'docs',
            'examples',
            'tests',
            'test',
        ], $dryrun);

        extract($result1);

        $result2 = $this->output->writeln('Removing files...');
        $this->removeFiles([
            '.gitignore',
            '.gitattributes',
            '.editorconfig',
            '.php_cs',
        ], $dryrun);

        $files += $result2['files'];
        $bytes += $result2['bytes'];

        $this->output->setDecorated(Output::TEXT_COLOR_GREEN);
        $this->output->writeln('Done.');
        $this->output->writeln('Removed ' . $files . ' files.');
        $this->output->writeln('Freed ' . $bytes . ' bytes.');
        exit(0);
    }

    /**
     * removeDirs
     *
     * @param array   $dirs   The directories to remove
     * @param boolean $dryrun Whether to run the command or not
     *
     * @return array
     */
    private function removeDirs(array $dirs, $dryrun = false)
    {
        $directory = new RecursiveDirectoryIterator(vendor_path(), RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);

        $filesRemoved = 0;
        $bytesRemoved = 0;
        foreach ($iterator as $info) {
            if ($info->isDir() && in_array($info->getFilename(), $dirs)) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($info->getRealPath(), RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($files as $fileinfo) {
                    if ($fileinfo->isFile()) {
                        $filesRemoved++;
                        $bytesRemoved += $fileinfo->getSize();
                        $removeFunction = 'unlink';
                    } else {
                        $removeFunction = 'rmdir';
                    }
                    if ($dryrun) {
                        $this->output->setDecorated(Output::TEXT_COLOR_YELLOW);
                        $this->output->writeln('Would remove ' . $fileinfo->getRealPath());
                    } else {
                        $this->output->setDecorated(Output::TEXT_COLOR_RED);
                        $this->output->writeln('Removing ' . $fileinfo->getRealPath());
                        $removeFunction($fileinfo->getRealPath());
                    }
                }

                // Remove the directory itself
                if ($dryrun) {
                    $this->output->setDecorated(Output::TEXT_COLOR_YELLOW);
                    $this->output->writeln('Would remove ' . $info->getRealPath());
                } else {
                    $this->output->setDecorated(Output::TEXT_COLOR_RED);
                    $this->output->writeln('Removing ' . $info->getRealPath());
                    rmdir($info->getRealPath());
                }
            }
        }

        return [
            'files' => $filesRemoved,
            'bytes' => $bytesRemoved,
        ];
    }

    /**
     * removeFiles
     *
     * @param array   $filenames The filenames to remove
     * @param boolean $dryrun    Whether to run the command or not
     *
     * @return array
     */
    private function removeFiles(array $filenames, $dryrun = false)
    {
        $files = [];
        $directory = new RecursiveDirectoryIterator(vendor_path(), RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
        $filesRemoved = 0;
        $bytesRemoved = 0;

        foreach ($iterator as $info) {
            if ($info->isFile() && in_array($info->getFilename(), $filenames)) {
                $files[] = $info->getRealPath();
            }
        }

        foreach ($files as $file) {
            $filesRemoved++;
            $bytesRemoved += filesize($file);

            if ($dryrun) {
                $this->output->setDecorated(Output::TEXT_COLOR_YELLOW);
                $this->output->writeln('Would remove ' . $file);
            } else {
                $this->output->setDecorated(Output::TEXT_COLOR_RED);
                $this->output->writeln('Removing ' . $file);
                unlink($file);
            }
        }

        return [
            'files' => $filesRemoved,
            'bytes' => $bytesRemoved,
        ];
    }
}
