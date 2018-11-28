<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Lib\Util;

class Rubric
{
    public function __set($name, $value) {
        throw new \Exception("Cannot add new property \$$name to instance of " . static::class);
    }

    public static function getNameSpace()
    {
        $namespace = explode('\\', static::class);
        array_pop($namespace);
        $namespace = implode('\\', $namespace);
        $namespace .= '\\';

        return $namespace;
    }

    public function load(array $data)
    {
        foreach (array_keys(get_class_vars(static::class)) as $propertyName) {
            if (array_key_exists($propertyName, $data)) {
                $this->loadAttribute(static::getNameSpace(), $propertyName, $data[$propertyName]);
            }
        }
    }

    public function loadAttribute($class, $attributeName, $data)
    {
        // todo: instantiating variable name class should be replaced with prototype pattern
        // todo: calling variable name function should not be used
        $attributeClassName = $class . Util::pascalize($attributeName);
        $setterMethodName = 'set' . Util::pascalize($attributeName);

        if (is_array($data)) {
            $attributeValue = new $attributeClassName();
            $attributeValue->load($data);
        } else if (is_object($data)) {
            $attributeValue = $data;
        } else {
            $attributeValue = $data;
        }
        $this->$setterMethodName($attributeValue);
    }

}