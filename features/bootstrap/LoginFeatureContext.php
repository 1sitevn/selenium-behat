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
class LoginFeatureContext extends RawMinkContext
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
     * @Given I am on Login page
     */
    public function iAmOnLoginPage()
    {
        $this->getSession()->visit('https://stg-web.wallet.9pay.mobi/dang-nhap');

        sleep(3);
    }

    /**
     * @When I fill in Phone field with :arg1
     */
    public function iFillInPhoneFieldWith($arg1)
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'input[name=PhoneVn]')
            ->setValue($arg1);

        sleep(3);
    }

    /**
     * @Then And I press Tiếp tục
     */
    public function andIPressTiepTuc()
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'a.btn_login')
            ->click();
    }

    /**
     * @Then I should see Verify OTP page
     */
    public function iShouldSeeVerifyOtpPage()
    {
        sleep(3);
    }

    /**
     * @When I fill in OTP field with :arg1
     */
    public function iFillInOtpFieldWith($arg1)
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'input[name=otp_code]')
            ->setValue($arg1);

        sleep(3);
    }

    /**
     * @Then And I press Tiếp tục in OTP Verify page
     */
    public function andIPressTiepTucInOtpVerifyPage()
    {
        sleep(3);
    }

    /**
     * @Then I should see Verify Password page
     */
    public function iShouldSeeVerifyPasswordPage()
    {
        sleep(3);
    }

    /**
     * @When I fill in Password field with :arg1
     */
    public function iFillInPasswordFieldWith($arg1)
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'input[name=passVn]')
            ->setValue($arg1);

        sleep(3);
    }

    /**
     * @Then And I press Đăng nhập
     */
    public function andIPressDangNhap()
    {
        $this->getSession()
            ->getPage()
            ->find('css', 'button.btn_login')
            ->click();
    }

    /**
     * @When I should see Home page
     */
    public function iShouldSeeHomePage()
    {
        sleep(25);
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
