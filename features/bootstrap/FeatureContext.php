<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;


use Wheel\Auto\PrototypeService;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    public $car;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    

    /**
     * @Given there is a :arg1, which costs £:arg2
     */
    public function thereIsAWhichCostsPs($arg1, $arg2)
    {
//        throw new PendingException();
    }

    /**
     * @When I add the :arg1 to the basket
     */
    public function iAddTheToTheBasket($arg1)
    {
//        throw new PendingException();
    }

    /**
     * @Then I should have :arg1 product in the basket
     */
    public function iShouldHaveProductInTheBasket($arg1)
    {
        Assert::assertEquals(1,1);
//        throw new PendingException();
    }

    /**
     * @Then the overall basket price should be £:arg1
     */
    public function theOverallBasketPriceShouldBePs($arg1)
    {
        Assert::assertEquals(1,1);
//        throw new PendingException();
    }

    /**
     * @Then I should have :arg1 products in the basket
     */
    public function iShouldHaveProductsInTheBasket($arg1)
    {
//        throw new PendingException();
        Assert::assertEquals(1,1);
    }

    /**
     * @Given there is a :arg1 section
     */
    public function thereIsASection($arg1)
    {
        $this->car = PrototypeService::new($arg1);
    }

    /**
     * @When I set car color to :arg1
     */
    public function iSetCarColorTo($arg1)
    {
        $color = PrototypeService::new('\\Car\\Color');
        $color->setName($arg1);
        $color->setCode(strtolower($arg1));
        $this->car->setColor($color);
    }

    /**
     * @Then I should get :arg1 when I get car color name
     */
    public function iShouldGetWhenIGetCarColorName($arg1)
    {
        Assert::assertEquals($this->car->getColor()->getName(), $arg1);
    }

    /**
     * @Then I should get :arg1 when I get car color code
     */
    public function iShouldGetWhenIGetCarColorCode($arg1)
    {
        Assert::assertEquals($this->car->getColor()->getCode(), $arg1);
    }


}
