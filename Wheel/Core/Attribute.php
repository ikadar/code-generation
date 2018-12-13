<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Lib\Util;

/**
 * Class Attribute
 *
 * @package Wheel\Core
 */
class Attribute
{
    // todo: they should be protected instead, however php default reflection can't see them. Try https://github.com/Roave/BetterReflection
    /**
     * @var
     */
    public static $name;
    /**
     * @var
     */
    public static $type;
    /**
     * @var
     */
    public static $required;
    /**
     * @var
     */
    public static $translatable;
    /**
     * @var
     */
    public static $defaultValue;
    /**
     * @var
     */
    public static $isCharacterizing;
    /**
     * @var
     */
    public static $isCascading;
    /**
     * @var
     */
    public static $referredRubrics;
    /**
     * @var
     */
    public static $allowNewSections;
    /**
     * @var
     */
    public static $allowExistingSections;

    /**
     * @var null
     */
    private $value = null;

    /**
     * Attribute constructor.
     */
    public function __construct()
    {
    }

    /**
     * Clone
     */
    public function __clone()
    {
        $this->__construct();
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


}