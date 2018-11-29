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