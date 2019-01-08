<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RubricTest extends TestCase
{

    private static $car;

    public static function setUpBeforeClass()
    {
        passthru('rm -rf Wheel/Auto', $result);

        $conceptJsonFile = 'concept.json';

        \Lib\Generator\PrototypeClassGenerator::initialize();

        // Generate concept
        $generator = new \Lib\Generator\GeneratorService();
        $generator->generateConceptSource($conceptJsonFile);

        \Lib\Generator\SourcePool::addSourceFile([
            'path' => \Lib\Generator\PrototypeClassGenerator::getPath(),
            'content' => \Lib\Generator\PrototypeClassGenerator::getSource()
        ]);

        \Lib\Generator\SourcePool::dump();
    }

    protected function setUp()
    {
        // ----------

        $carData = [
            '__type' => 'Car.Car',
//        'plate_number' => 'gh-33-17',
            'color' => [
//            '__type' => 'Car.Brand',
                '__type' => 'Car.Color',
                'name' => 'Blue',
                'code' => 'blue',
            ],
            'brand' => [
                '__type' => 'Car.Brand',
                'name' => 'Lada',
                'code' => 'lada',
                'producer' => [
                    '__type' => 'Car.Producer',
                    'name' => 'Lada Factory',
                    'code' => 'lada_factory',
                    'default_plate_number' => 'lada-fac-001'
                ],
                'default_plate_number' => 'lada-001'
            ],
            'owner' => [
                '__type' => 'Car.Person',
                'name' => 'John Doe',
                'age' => 30,
                'gender' => [
                    '__type' => 'Car.Gender',
                    'name' => 'Male',
                    'code' => 'male'
                ]
            ]
        ];

        self::$car = \Wheel\Core\PrototypeService::new('\Car\CarProxy');
        self::$car->load($carData);
    }

    public function testGeneratedDirectoriesAndFiles()
    {
        $this->assertDirectoryExists('Wheel/Auto');
        $this->assertFileExists('Wheel/Auto/PrototypeService.php');

        $o = new \Wheel\Auto\PrototypeService();
        $this->assertInstanceOf('\Wheel\Auto\PrototypeService', $o);
        $this->assertClassHasStaticAttribute('prototypes', '\Wheel\Auto\PrototypeService');
        $this->assertClassHasStaticAttribute('assignments', '\Wheel\Auto\PrototypeService');
//        $this->assertIsArray(\Wheel\Auto\PrototypeService::$prototypes);

        $this->assertDirectoryExists('Wheel/Auto/Concept');
        $this->assertFileExists('Wheel/Auto/Concept/Car.php');
        $this->assertFileExists('Wheel/Auto/Concept/CarBrand.php');
        $this->assertFileExists('Wheel/Auto/Concept/CarColor.php');
        $this->assertFileExists('Wheel/Auto/Concept/CarGender.php');
        $this->assertFileExists('Wheel/Auto/Concept/CarPerson.php');
        $this->assertFileExists('Wheel/Auto/Concept/CarProducer.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Brand.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/BrandProxy.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Car.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/CarProxy.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Color.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/ColorProxy.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Gender.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/GenderProxy.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Person.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/PersonProxy.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Producer.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/ProducerProxy.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Brand');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Brand/CodeAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Brand/DefaultPlateNumberAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Brand/NameAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Brand/ProducerAttribute.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Car');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Car/BrandAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Car/ColorAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Car/OwnerAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Car/PlateNumberAttribute.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Color');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Color/CodeAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Color/NameAttribute.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Gender');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Gender/CodeAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Gender/NameAttribute.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Person');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Person/AgeAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Person/GenderAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Person/NameAttribute.php');

        $this->assertDirectoryExists('Wheel/Auto/Concept/Car/Producer');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Producer/CodeAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Producer/DefaultPlateNumberAttribute.php');
        $this->assertFileExists('Wheel/Auto/Concept/Car/Producer/NameAttribute.php');
    }

    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertEquals(
            'user@example.com',
            'user@example.com'
        );
    }

    /**
     * @expectedException Exception
     */
    public function testCannotAddNewPropertyToRubric(): void
    {
        $r = new \Wheel\Core\Rubric();
        $r->aaa = 'bbb';
    }

    public function testRubricGetNameSpace(): void
    {
        $car = new \Wheel\Auto\Concept\Car\Car();

        $this->assertEquals('\\Wheel\\Auto\\Concept\\Car\\', $car::getNameSpace());
    }

    public function testLoad(): void
    {
        $this->assertEquals('Wheel\Auto\Concept\Car\Color', get_class(self::$car->getColor()));
        $this->assertEquals('Blue', self::$car->getColor()->getName());
        $this->assertEquals('blue', self::$car->getColor()->getCode());

        $this->assertEquals('Wheel\Auto\Concept\Car\Brand', get_class(self::$car->getBrand()));
        $this->assertEquals('Lada', self::$car->getBrand()->getName());
        $this->assertEquals('lada', self::$car->getBrand()->getCode());

        $this->assertEquals('Wheel\Auto\Concept\Car\Producer', get_class(self::$car->getBrand()->getProducer()));
        $this->assertEquals('Lada Factory', self::$car->getBrand()->getProducer()->getName());
        $this->assertEquals('lada_factory', self::$car->getBrand()->getProducer()->getCode());
        $this->assertEquals('LADA-FAC-001', self::$car->getBrand()->getProducer()->getDefaultPlateNumber());

        $this->assertEquals('Wheel\Auto\Concept\Car\Person', get_class(self::$car->getOwner()));
        $this->assertEquals('John Doe', self::$car->getOwner()->getName());
        $this->assertEquals('30', self::$car->getOwner()->getAge());

        $this->assertEquals('Wheel\Auto\Concept\Car\Gender', get_class(self::$car->getOwner()->getGender()));
        $this->assertEquals('Male', self::$car->getOwner()->getGender()->getName());
        $this->assertEquals('male', self::$car->getOwner()->getGender()->getCode());
    }

    public function testValidation(): void
    {
        self::$car->validate();
        $this->assertEquals('a', 'b');

    }
}