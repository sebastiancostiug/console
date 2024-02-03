<?php
/**
 * @package     Console
 *
 * @subpackage  Input interface
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    interfaces
 *
 * @since       2023-12-01
 */

namespace console\interfaces;

/**
 * Represents an input interface for console commands.
 */
interface InputInterface
{
    /**
     * Returns the argument value for a given argument name.
     *
     * @param string $name The argument name
     *
     * @return mixed The argument value
     */
    public function getArgument($name);

    /**
     * Sets an argument value by name.
     *
     * @param string $name  The argument name
     * @param string $value The argument value
     */
    public function setArgument($name, $value);

    /**
     * Returns the option value for a given option name.
     *
     * @param string $name The option name
     *
     * @return mixed The option value
     */
    public function getOption($name);

    /**
     * Sets an option value by name.
     *
     * @param string $name  The option name
     * @param string $value The option value
     */
    public function setOption($name, $value);
}
