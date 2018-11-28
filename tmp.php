<?php
//anonymous class somewhere in the core of project like src/lib or src/service

namespace valami\masik;

trait Translatable {
    public function translate()
    {
        return null;
    }
}

abstract class BaseAttribute
{
    abstract public function getProp($prop);
}

class BaseAttribute2
{
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } else {
            throw new \Exception('Undefined property ' . $name . '.');
        }
    }

    public function getProp($prop)
    {
        return $this->$prop;
    }
}

class BaseRubric
{
    protected $attributes;

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } else {
            throw new \Exception('Undefined property ' . $name . '.');
        }
    }
}

class AttributeFactory
{
    public function create($conf) {

        return new class($conf) extends BaseAttribute {

            public function __construct($conf)
            {
                $this->editable = $conf['editable'];
                $this->characterizing = $conf['characterizing'];
            }

            public function getProp($prop)
            {
                return $this->$prop;
            }
        };
    }
}

class RubricFactory
{
    public function create($conf) {
        return new class($conf) {

            use Translatable;

            public function __construct($conf)
            {

                $af = new AttributeFactory($conf);

                foreach ($conf['attributes'] as $key => $attributeConf) {
                    $this->attributes[$key] = $af->create($attributeConf);
                }

            }
        };
    }
}


// CONS of anonymous class
// no namespace (? is it really a con?)
// no final class possible (For the sake of integrity, we should not allow to change our classes that represents business objects. To achieve that, it is a good practice to make them final, and provide access to them via proxy classes.)
// each instantiation time we have to execute configuration validation (using prototyping that can be handled)
// to see diffs between current and previous classes you need to check json definitions and factory code together instead of just check git diff of classes
// cannot add type hints to attribute setters
// cannot add return type to attribute getters
// you have to use magic methods to keep consumer code nice and safe
//  - compromise code completion
//  - degrade performance
//  - degrade security
// more difficult to debug: Notice:  Undefined property: class@anonymous::$color instead of Notice:  Undefined property: Car::$color
// during development you can't just change generated code to try out things, you have to change factory code, that will affect behaviour of all classes at a time
// you can't extend classes from different base classes, so we can't create different base classes for different purposes (e.g. translatable, moderated, etc.) - using treats that can be solved
// testing
// dependency injection (?)
// client code can't be agnostic about class composition
// you can't change class implementation based on configuration (e.g. if an attribute is read-only, you can't just remove its setter)
// cannot live different concepts from different versions together


// PROS of code generation
// auto generated documentation


$t0 = microtime(true);

$jsonCar = '{
    "attributes": {
        "color": {
            "editable": true,
            "characterizing": 5
        },
        "plate_number": {
            "editable": true,
            "characterizing": 5
        }
    }
}';
$carConf = json_decode($jsonCar, true);


$rf = new RubricFactory();

$carObject = $rf->create($carConf);
$t1 = microtime(true);
var_dump(round($t1-$t0, 4));


print_r(get_class($carObject));

$carObjectReflection = new \ReflectionClass($carObject);

print_r($carObjectReflection);

print_r($carObject);
print_r($carObject->attributes['color']->getProp('characterizing'));
var_dump($carObject->translate());
//print_r($carObject->color->characterizing);
//print_r($carObject->getProp('editablex'));
//var_dump($carObject);