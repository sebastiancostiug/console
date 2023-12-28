<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  ConsoleKernel
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    slim-api-skeleton
 * @see
 *
 * @since       2023-12-02
 *
 */

namespace console;

use bootstrap\foundation\bootstrappers\EnvironmentDetector;
use bootstrap\foundation\bootstrappers\EnvironmentVariables;
use bootstrap\foundation\bootstrappers\LoadConsoleEnvironment;
use bootstrap\foundation\bootstrappers\ServiceProviders;
use bootstrap\foundation\Kernel;
use console\commands\CreateMigration;
use console\commands\RunMigration;

/**
 * ConsoleKernel class
 */
class ConsoleKernel extends Kernel
{
    /**
     * The array of console commands.
     *
     * @var array
     */
    public array $commands = [
        CreateMigration::class,
        RunMigration::class,
    ];

    /**
     * @var array $bootstrappers Register application bootstrap loaders
     */
    public array $bootstrappers = [
        EnvironmentDetector::class,
        LoadConsoleEnvironment::class,
        EnvironmentVariables::class,
        ServiceProviders::class,
    ];
}
