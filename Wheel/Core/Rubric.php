<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Lib\Util;
use Wheel\Concept\PrototypeService;

class Rubric
{

    /**
     * Clone
     */
    public function __clone()
    {
        $this->__construct();
    }

    public function __set($name, $value) {
        throw new \Exception("Cannot add new property \$$name to instance of " . static::class);
    }

    public static function getNameSpace()
    {
        $namespace = explode('\\', static::class);
        array_pop($namespace);
        $namespace = implode('\\', $namespace);
        $namespace = '\\' . $namespace . '\\';
//        $namespace .= '\\';

        return $namespace;
    }

    public function load(array $data)
    {
        // TODO 00: here property name and rubric are supposed to be the same, that is wrong
        foreach (array_keys(get_class_vars(static::class)) as $propertyName) {
            if (array_key_exists($propertyName, $data)) {
                $this->loadAttribute(static::getNameSpace(), $propertyName, $data[$propertyName]);
            }
        }
    }

    public function loadAttribute($class, $attributeName, $data)
    {
        // Todo 02: maybe we will need to be able to set attribute to null
        if ($data === null) {
            return;
        }

        $attributeClassName = $class . Util::pascalize($attributeName);
        $setterMethodName = 'set' . Util::pascalize($attributeName);

        if (is_array($data)) {
            $attributeValue = PrototypeService::new($attributeClassName);
//            $attributeValue = new $attributeClassName();
            $attributeValue->load($data);
        } else if (is_object($data)) {
            $attributeValue = $data->get();
//            $attributeValue = $data;
        } else {
            $attributeValue = $data;
        }
        // todo: calling variable name function should not be used
        $this->$setterMethodName($attributeValue);
    }

    public function getData(): array
    {
        $data = get_object_vars($this);
        foreach ($data as $attributeName => $attribute) {
            if (is_a($attribute->getValue(), Rubric::class)) {
                $data[$attributeName] = $attribute->getValue()->getData();
            } else {
                $data[$attributeName] = $attribute->getValue();
            }
        }
        return $data;
    }

    public function cascade($cascadingSourcePaths)
    {
        $cascadedValue = null;
        foreach ($cascadingSourcePaths as $cascadingSourcePath) {
            $cascadedValue = $this;
            foreach ($cascadingSourcePath as $cascadingSourceGetter) {
                $cascadedValue = call_user_func([$cascadedValue, $cascadingSourceGetter]);
            }
            if ($cascadedValue !== null) {
                break;
            }
        }
        return $cascadedValue;
    }

}