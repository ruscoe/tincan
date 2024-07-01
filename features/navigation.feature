Feature: Navigation
  In order to navigate the forum
  As a forum user
  I need to be able to see the main navigation

  Scenario: A forum user views the main navigation
    Given I am on "/"
    Then I should see "Create Account"
    And I should see "Log in"
