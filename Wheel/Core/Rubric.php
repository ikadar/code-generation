<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 8:27
 */

namespace Wheel\Core;

use Lib\Util;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validation;
use Wheel\Core\PrototypeService;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Rubric
 *
 * @package Wheel\Core
 */
class Rubric
{

    /**
     * Clone
     */
    public function __clone()
    {
        $this->__construct();
    }

    /**
     * @param  $name
     * @param  $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        throw new \Exception("Cannot add new property \$$name to instance of " . static::class);
    }

    /**
     * @return array|string
     */
    public static function getNameSpace(): string
    {
        $namespace = explode('\\', static::class);
        array_pop($namespace);
        $namespace = implode('\\', $namespace);
        $namespace = '\\' . $namespace . '\\';

        return $namespace;
    }

    /**
     * @param  array $data
     * @throws \Exception
     */
    public function load(array $data)
    {
        foreach (array_keys(get_class_vars(static::class)) as $propertyName) {
            if (array_key_exists($propertyName, $data)) {
                $this->loadAttribute($propertyName, $data[$propertyName]);
            }
        }
    }

    /**
     * @param  $attributeName
     * @param  $data
     * @throws \Exception
     */
    protected function loadAttribute($attributeName, $data)
    {
        $attributeValue = null;

        if (is_array($data)) {
            $pathElements = explode('.', $data['__type']);
//            $pathElements = array_merge(explode('\\', Util::getAutoGenConceptNS()), explode('.', $data['__type']));
            $referredRubricClass = '\\' . implode('\\', $pathElements);

            $attributeValue = PrototypeService::new($referredRubricClass);
            $attributeValue->load($data);
        } else if (is_object($data)) {
            $attributeValue = $data->get();
        }

        $attributeValue = $attributeValue ?: $data;

        // call attribute setter method of rubric class
        $setterMethodName = 'set' . Util::pascalize($attributeName);
        call_user_func_array([$this, $setterMethodName], [$attributeValue]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        // TODO 03: move this into path builder class
        $data = get_object_vars($this);
        $pathElements = explode('\\', get_class($this));
        array_shift($pathElements); // remove "Auto"
        array_shift($pathElements); // remove "Wheel"
        array_shift($pathElements); // remove concept

        foreach ($data as $attributeName => $attribute) {
            if (is_a($attribute->getValue(), Rubric::class)) {
                $data[$attributeName] = $attribute->getValue()->getData();
            } else {
                $data[$attributeName] = $attribute->getValue();
            }
        }

        $data['__type'] = implode('.', $pathElements); // dotted Wheel path of rubric

        return $data;
    }

    /**
     * @param  $cascadingSourcePaths
     * @return mixed|null|Rubric
     */
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

    public function validate()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        $violations = $validator->validate($this);

        var_dump($violations);
    }


}