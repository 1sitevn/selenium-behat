<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext
{
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
     * @Given I am on homepage
     */
    public function iAmOnHomepage()
    {
        $this->getSession()->visit('https://www.google.com');
    }

    /**
     * @When I fill in :arg1 with :arg2
     */
    public function iFillInWith($arg1, $arg2)
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'input[name=q]')
            ->setValue($arg2);
    }

    /**
     * @Then And I press :arg1
     */
    public function andIPress($arg1)
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'form#tsf')
            ->submit();
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        $element = $this->getSession()
            ->getPage()
            ->find('css', 'input[name=q]');

        $value = $element->getValue();

        if (empty($value)) {
            throw new \Exception(sprintf("The page '%s' does not contain '%s'", $this->getSession()->getCurrentUrl(), $arg1));
        }

        if ($value != $arg1) {
            throw new \Exception(sprintf("The page '%s' does not same contain '%s'", $this->getSession()->getCurrentUrl(), $arg1));
        }
    }
}
