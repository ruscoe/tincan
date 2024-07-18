Feature: Authentication
  In order to use the forum
  As a forum user
  I need to be able to create and log into an account

  Scenario: A new forum user creates an account
    Given I am on "/"
    When I follow "Create Account"
    Then the ".section-header" element should contain "Create Account"
    When I fill in the following:
      | username | TestUser01             |
      | email    | testuser01@example.org |
      | password | T3stP@ss01             |
    And press "Create account"
    Then I should see "Logged in as TestUser01"
    And I should see "Log Out"

  Scenario: An existing forum user logs into their account.
    Given users exist:
    | username   | email                  | password   | role_id |
    | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
    And I am on "/"
    When I follow "Log In"
    Then the ".section-header" element should contain "Log In"
    When I fill in the following:
      | username | TestUser01 |
      | password | T3stP@ss01 |
    And press "Log in"
    Then I should see "Logged in as TestUser01"
    And I should see "Log Out"
