Feature: Test google search

  Scenario: Open google search home page
    Given I am on homepage
    When I fill in "Search" with "Selenium behat"
    Then And I press "Google Search"
    And I should see "Selenium behat"
