Feature: Test 9Pay Login

  Scenario: Open 9Pay Login page
    Given I am on Login page
    When I fill in Phone field with "0829241189"
    Then And I press Tiếp tục
    And I should see Verify OTP page
    When I fill in OTP field with "123456"
    Then And I press Tiếp tục in OTP Verify page
    And I should see Verify Password page
    When I fill in Password field with "142857"
    Then And I press Đăng nhập
    And I should see Home page
