<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  Console Output interface
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

namespace seb\console\interfaces;

interface OutputInterface
{
    const VERBOSITY_QUIET        = 0;
    const VERBOSITY_NORMAL       = 1;
    const VERBOSITY_VERBOSE      = 2;
    const VERBOSITY_VERY_VERBOSE = 3;
    const VERBOSITY_DEBUG        = 4;

    const OUTPUT_NORMAL = 0;
    const OUTPUT_RAW    = 1;
    const OUTPUT_PLAIN  = 2;

    const TEXT_COLOR_BLACK        = "\033[0;30m";
    const TEXT_COLOR_DARK_GRAY    = "\033[1;30m";
    const TEXT_COLOR_BLUE         = "\033[0;34m";
    const TEXT_COLOR_LIGHT_BLUE   = "\033[1;34m";
    const TEXT_COLOR_GREEN        = "\033[0;32m";
    const TEXT_COLOR_LIGHT_GREEN  = "\033[1;32m";
    const TEXT_COLOR_CYAN         = "\033[0;36m";
    const TEXT_COLOR_LIGHT_CYAN   = "\033[1;36m";
    const TEXT_COLOR_RED          = "\033[0;31m";
    const TEXT_COLOR_LIGHT_RED    = "\033[1;31m";
    const TEXT_COLOR_PURPLE       = "\033[0;35m";
    const TEXT_COLOR_LIGHT_PURPLE = "\033[1;35m";
    const TEXT_COLOR_BROWN        = "\033[0;33m";
    const TEXT_COLOR_YELLOW       = "\033[1;33m";
    const TEXT_COLOR_LIGHT_GRAY   = "\033[0;37m";
    const TEXT_COLOR_WHITE        = "\033[1;37m";
    const TEXT_COLOR_DEFAULT      = "\033[39m";

    const TEXT_STYLE_BOLD       = "\033[1m";
    const TEXT_STYLE_DIM        = "\033[2m";
    const TEXT_STYLE_UNDERLINE  = "\033[4m";
    const TEXT_STYLE_BLINK      = "\033[5m";
    const TEXT_STYLE_REVERSE    = "\033[7m";
    const TEXT_STYLE_HIDDEN     = "\033[8m";
    const TEXT_STYLE_DEFAULT    = "\033[0m";

    /**
     * Writes a message to the output.
     *
     * @param string  $message A message to write to the output
     * @param boolean $newline Whether to add a newline or not
     *
     * @return void
     */
    public function write($message, $newline = false);

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string $message A message to write to the output
     *
     * @return void
     */
    public function writeln($message);

    /**
     * Sets the verbosity of the output.
     *
     * @param integer $level The level of verbosity (one of the VERBOSITY constants)
     *
     * @return void
     */
    public function setVerbosity($level);

    /**
     * Gets the current verbosity of the output.
     *
     * @return integer The current level of verbosity (one of the VERBOSITY constants)
     */
    public function getVerbosity();

    /**
     * Sets whether to decorate messages.
     *
     * @param boolean $decorate Whether to decorate messages
     *
     * @return void
     */
    public function setDecorated($decorate);

    /**
     * Gets whether messages are decorated.
     *
     * @return boolean true if messages are decorated, false otherwise
     */
    public function getDecorated();
}
