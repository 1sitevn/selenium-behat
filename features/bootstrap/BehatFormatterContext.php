<?php


namespace OneSite\SeleniumBehat;


use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Class BehatFormatterContext
 * @package OneSite\SeleniumBehat
 */
class BehatFormatterContext extends \elkan\BehatFormatter\Context\BehatFormatterContext
{
    /**
     * @var
     */
    private $currentScenario;

    /**
     * @BeforeScenario
     */
    public function setUpScreenshotScenarioEnvironmentElkanBehatFormatter(BeforeScenarioScope $scope)
    {
        $this->currentScenario = $scope->getScenario();
    }

    /**
     * Take screen-shot when step fails.
     * Take screenshot on result step (Then)
     * Works only with Selenium2Driver.
     *
     * @AfterStep
     * @param AfterStepScope $scope
     */
    public function afterStepScreenShotOnFailure(AfterStepScope $scope)
    {
        $currentSuite = self::$currentSuite;

        //if test has failed, and is not an api test, get screenshot
        if (!$scope->getTestResult()->isPassed() || in_array($scope->getStep()->getKeywordType(), ["Then", "When"])) {
            $driver = $this->getSession()->getDriver();
            if (!$driver instanceof Selenium2Driver) {
                return;
            }

            //create filename string
            $fileName = $currentSuite . "." . basename($scope->getFeature()->getFile()) . '.' . $this->currentScenario->getLine() . '.' . $scope->getStep()->getLine() . '.png';
            $fileName = str_replace('.feature', '', $fileName);

            /*
             * Determine destination folder!
             * This must be equal to the printer output path.
             * How the fuck do I get that in here???
             *
             * Fuck it, create a temporary folder for the screenshots and
             * let the Printer copy those to the assets folder.
             * Spend too many time here! And output is not the contexts concern, it's the printers concern.
             */

            $temp_destination = getcwd() . DIRECTORY_SEPARATOR . ".tmp_behatFormatter";
            if (!is_dir($temp_destination)) {
                mkdir($temp_destination, 0777, true);
            }

            $this->saveScreenshot($fileName, $temp_destination);
        }

        // Let us save the page source code on errors:
        // It helps us debug the test.

        if (!$scope->getTestResult()->isPassed()) {
            //create filename string
            $fileName = $currentSuite . "." . basename($scope->getFeature()->getFile()) . '.' . $scope->getStep()->getLine() . '.html';
            $fileName = str_replace('.feature', '', $fileName);

            $htmlContent = sprintf('<!DOCTYPE html><html>%s</html>', $this->getSession()->getPage()->getHtml());

            $temp_destination = getcwd() . DIRECTORY_SEPARATOR . ".tmp_behatFormatter";
            if (!is_dir($temp_destination)) {
                mkdir($temp_destination, 0777, true);
            }

            file_put_contents(implode(DIRECTORY_SEPARATOR, array($temp_destination, $fileName)), $htmlContent);
        }

    }

}
