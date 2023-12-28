<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  Console
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

/**
 * Represents a command line input argument.
 */
class InputArgument
{
    /**
     * Represents a required input argument.
     */
    const REQUIRED = 1;
    /**
     * Represents a optional input argument.
     */
    const OPTIONAL = 2;
    /**
     * Represents a array input argument.
     */
    const IS_ARRAY = 4;

    /**
     * @var string $name The name of the input argument.
     */
    protected $name;
    /**
     * @var string $mode The mode of the input argument.
     */
    protected $mode;
    /**
     * @var string $description The description of the input argument.
     */
    protected $description;
    /**
     * @var mixed $default The default value for the input argument.
     */
    protected $default;

    /**
     * Class representing a command line input argument.
     *
     * An input argument can have a name, mode, description, and default value.
     * The mode determines whether the argument is required or optional.
     * The default mode is OPTIONAL.
     *
     * @param string       $name        The name of the argument.
     * @param integer|null $mode        The mode of the argument (OPTIONAL, REQUIRED, or IS_ARRAY).
     * @param string       $description The description of the argument.
     * @param mixed        $default     The default value of the argument.
     *
     * @throws InvalidArgumentException If the mode is not valid.
     *
     * @return void
     */
    public function __construct($name, $mode = null, $description = '', mixed $default = null)
    {
        throw_when((!is_int($mode) || $mode > 7 || $mode < 1), 'Argument mode "%s" is not valid.');

        if ($mode === null) {
            $mode = self::OPTIONAL;
        }

        $this->name = $name;
        $this->mode = $mode;
        $this->description = $description;

        $this->setDefault($default);
    }

    /**
     * Get the name of the input argument.
     *
     * @return string The name of the input argument.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if the input argument is required.
     *
     * @return boolean Returns true if the input argument is required, false otherwise.
     */
    public function isRequired()
    {
        return self::REQUIRED === (self::REQUIRED & $this->mode);
    }

    /**
     * Check if the input argument is an array.
     *
     * @return boolean Returns true if the input argument is an array, false otherwise.
     */
    public function isArray()
    {
        return self::IS_ARRAY === (self::IS_ARRAY & $this->mode);
    }

    /**
     * Set the description for the input argument.
     *
     * @param string $description The description of the input argument.
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get the description of the input argument.
     *
     * @return string The description of the input argument.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the default value for the input argument.
     *
     * @param mixed $default The default value for the input argument.
     *
     * @throws LogicException If a default value is set for a required argument.
     * @throws LogicException If the default value for an array argument is not an array.
     *
     * @return void
     */
    public function setDefault(mixed $default = null)
    {
        throw_when((self::REQUIRED === $this->mode && null !== $default), 'Cannot set a default value except for InputArgument::OPTIONAL mode.');

        if ($this->isArray()) {
            throw_when((null !== $default && !is_array($default)), 'A default value for an array argument must be an array.');
            if (null === $default) {
                $default = [];
            }
        }

        $this->default = $default;
    }

    /**
     * Get the default value of the input argument.
     *
     * @return mixed The default value of the input argument.
     */
    public function getDefault()
    {
        return $this->default;
    }
}
