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

namespace console\components;

use console\interfaces\InputInterface;

/**
 * Class Input
 *
 * Represents an input for a console command.
 *
 * @implements InputInterface
 */
class Input implements InputInterface
{
    /**
     * @var array $arguments The input arguments.
     */
    protected $arguments;

    /**
     * @var array $options The input options.
     */
    protected $options;

    /**
     * Input constructor.
     *
     * @param array $arguments The input arguments.
     * @param array $options   The input options.
     */
    public function __construct(array $arguments = [], array $options = [])
    {
        $this->arguments = $arguments;
        $this->options = $options;
    }

    /**
     * Get the value of a specific argument.
     *
     * @param string $name The name of the argument.
     *
     * @return mixed The value of the argument.
     * @throws Exception If the argument does not exist.
     */
    public function getArgument($name)
    {
        throw_when(!array_key_exists($name, $this->arguments), "The {$name} argument does not exist.");

        return $this->arguments[$name];
    }

    /**
     * Set the value of a specific argument.
     *
     * @param string $name  The name of the argument.
     * @param mixed  $value The value of the argument.
     *
     * @return void
     * @throws Exception If the argument does not exist.
     */
    public function setArgument($name, mixed $value)
    {
        throw_when(!array_key_exists($name, $this->arguments), "The {$name} argument does not exist.");

        $this->arguments[$name] = $value;
    }

    /**
     * Get the value of a specific option.
     *
     * @param string $name The name of the option.
     *
     * @return mixed The value of the option.
     * @throws Exception If the option does not exist.
     */
    public function getOption($name)
    {
        throw_when(!array_key_exists($name, $this->options), "The {$name} option does not exist.");

        return $this->options[$name];
    }

    /**
     * Set the value of a specific option.
     *
     * @param string $name  The name of the option.
     * @param mixed  $value The value of the option.
     *
     * @return void
     * @throws Exception If the option does not exist.
     */
    public function setOption($name, mixed $value)
    {
        throw_when(!array_key_exists($name, $this->options), "The {$name} option does not exist.");

        $this->options[$name] = $value;
    }
}
