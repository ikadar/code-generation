<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Lib\Util;

class Attribute
{
    // todo: they should be protected instead, however php default reflection can't see them. Try https://github.com/Roave/BetterReflection
    public static $name;
    public static $type;
    public static $required;
    public static $translatable;
    public static $defaultValue;
    public static $isCharacterizing;
    public static $isCascading;
    public static $referredRubrics;
    public static $allowNewSections;
    public static $allowExistingSections;

    private $value = null;

    public function __construct()
    {
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