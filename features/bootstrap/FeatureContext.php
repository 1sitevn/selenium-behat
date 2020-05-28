<?php

namespace OneSite\SeleniumBehat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext
{
    /**
     * @var
     */
    private $screenShotPath;

    /**
     * FeatureContext constructor.
     * @param $screen_shot_path
     */
    public function __construct($screen_shot_path)
    {
        $this->screenShotPath = $screen_shot_path;
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

    /**
     * Take screen-shot when step fails. Works only with Selenium2Driver.
     *
     * @AfterStep
     * @param AfterStepScope $scope
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope)
    {
        if (99 === $scope->getTestResult()->getResultCode()) {
            $driver = $this->getSession()->getDriver();

            if (!$driver instanceof Selenium2Driver) {
                return;
            }

            if (!is_dir($this->screenShotPath)) {
                mkdir($this->screenShotPath, 0777, true);
            }

            $filename = sprintf(
                '%s_%s_%s.%s',
                $this->getMinkParameter('browser_name'),
                date('Ymd') . '-' . date('His'),
                uniqid('', true),
                'png'
            );

            $this->saveScreenshot($filename, $this->screenShotPath);
        }
    }
}
