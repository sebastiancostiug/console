<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  RunMigration
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

namespace core\console\commands;

use seb\common\Collection;
use seb\console\components\Command;
use seb\console\components\InputArgument;

/**
 * Migration class
 */
class RunMigration extends Command
{
    protected $name        = 'migration:run';
    protected $help        = 'Run migrations';
    protected $description = 'Run migrations';

    public function __construct()
    {
        parent::__construct();

        $this->addArgument('direction', InputArgument::OPTIONAL, 'The direction of the migration');
    }

    /**
     * handle
     *
     * @param  mixed $args
     *
     * @return void
     */
    public function handler()
    {
        $result = migrate($this->input->getArgument('direction'));

        if ($result['status'] === 'success') {
            $this->output->writeln($result['message']);
        } else {
            $errors = new Collection($result['errors']);

            $errorsOutput = $errors->map(function ($error) {
                return $error['file'] . ': ' . $error['error'];
            })->implode(PHP_EOL);

            $this->output->writeln($result['message']);
            $this->output->writeln($errorsOutput);
        }
        exit(0);
    }
}
