<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  Console input
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

use seb\console\interfaces\OutputInterface;

/**
 * Class Output
 *
 * Represents the output component for console applications.
 */
class Output implements OutputInterface
{
    /**
     * @var integer The verbosity level of the output.
     */
    protected $verbosity = self::VERBOSITY_NORMAL;

    /**
     * @var string The decoration for the output.
     */
    protected ?string $decorated = null;

    /**
     * Writes a message to the output.
     *
     * @param string  $message The message to write.
     * @param boolean $newline Whether to add a newline character at the end of the message.
     *
     * @return void
     */
    public function write($message, $newline = false)
    {
        $message = $this->decorate($message, $this->decorated);

        if ($newline) {
            $message .= PHP_EOL;
        }

        echo $message;
    }

    /**
     * Writes a message to the output followed by a newline character.
     *
     * @param string $message The message to write.
     *
     * @return void
     */
    public function writeln($message)
    {
        $this->write($message, true);
    }

    /**
     * Sets the verbosity level of the output.
     *
     * @param integer $level The verbosity level.
     * @return void
     */
    public function setVerbosity($level)
    {
        $this->verbosity = $level;
    }

    /**
     * Gets the verbosity level of the output.
     *
     * @return integer The verbosity level.
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * Decorates a message with a specified decoration if the output is set to be decorated.
     *
     * @param string $message The message to be decorated.
     *
     * @return string The decorated message.
     */
    public function decorate($message)
    {
        if ($this->decorated && is_string($this->decorated)) {
            return $this->decorated . $message . Output::TEXT_COLOR_DEFAULT;
        }

        return $message;
    }

    /**
     * Sets whether the output should be decorated.
     *
     * @param boolean $decorate Whether to decorate the output.
     * @return void
     */
    public function setDecorated($decorate)
    {
        $this->decorated = $decorate;
    }

    /**
     * Gets whether the output should be decorated.
     *
     * @return boolean Whether the output should be decorated.
     */
    public function getDecorated()
    {
        return $this->decorated;
    }
}
