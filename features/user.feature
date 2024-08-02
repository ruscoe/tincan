Feature: User
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

  Scenario: A new forum user creates an account with an existing username
    Given users exist:
    | username   | email                  | password   | role_id |
    | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
    Given I am on "/"
    When I follow "Create Account"
    And I fill in the following:
      | username | TestUser01             |
      | email    | testuser01@example.org |
      | password | T3stP@ss01             |
    And press "Create account"
    Then the ".errors" element should contain "This username already exists; please choose another."

  Scenario: A new forum user creates an account with an existing email address
    Given users exist:
    | username   | email                  | password   | role_id |
    | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
    Given I am on "/"
    When I follow "Create Account"
    And I fill in the following:
      | username | TestUser02             |
      | email    | testuser01@example.org |
      | password | T3stP@ss01             |
    And press "Create account"
    Then the ".errors" element should contain "An account with this email address already exists."

  Scenario: A new forum user creates an account with a short username
    Given I am on "/"
    When I follow "Create Account"
    And I fill in the following:
      | username | me                     |
      | email    | testuser01@example.org |
      | password | T3stP@ss01             |
    And press "Create account"
    Then the ".errors" element should contain "Please choose a longer username."

  Scenario: A new forum user creates an account with a short password
    Given I am on "/"
    When I follow "Create Account"
    And I fill in the following:
      | username | TestUser01             |
      | email    | testuser01@example.org |
      | password | 123                    |
    And press "Create account"
    Then the ".errors" element should contain "Please choose a longer password."

  Scenario: A new forum user creates an account with an invalid email address
    Given I am on "/"
    When I follow "Create Account"
    And I fill in the following:
      | username | TestUser01 |
      | email    |            |
      | password | T3stP@ss01 |
    And press "Create account"
    Then the ".errors" element should contain "Please check your email address has been entered correctly."

  Scenario: An existing forum user logs into their account.
    Given users exist:
    | username   | email                  | password   | role_id |
    | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
    Given I am on "/"
    When I follow "Log In"
    Then the ".section-header" element should contain "Log In"
    When I fill in the following:
      | username | TestUser01 |
      | password | T3stP@ss01 |
    And press "Log in"
    Then I should see "Logged in as TestUser01"
    And I should see "Log Out"

  Scenario: A forum user cannot log in with the wrong password
    Given users exist:
    | username   | email                  | password   | role_id |
    | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
    Given I am on "/"
    When I follow "Log In"
    Then the ".section-header" element should contain "Log In"
    When I fill in the following:
      | username | TestUser01 |
      | password | 123123123  |
    And press "Log in"
    Then the ".errors" element should contain "Could not find an account with that username and password."

  Scenario: A suspended forum user cannot log in
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    | TestUser01  | testuser01@example.org  | T3stP@ss01 | 1       |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Users"
    And I follow "Edit" in the row containing "TestUser01"
    And I check "suspended"
    And I press "Update User"
    And I follow "Log Out"
    And I follow "Log In"
    And I fill in the following:
      | username | TestUser01 |
      | password | T3stP@ss01 |
    And press "Log in"
    Then the ".errors" element should contain "Your account can no longer log in."

  Scenario: A user changes their password
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestUser01  | testuser01@example.org  | T3stP@ss01 | 1       |
    Given I am logged in as "testuser01@example.org"
    Given I am on "/"
    And I follow "Account"
    And I fill in the following:
    | current_pass | T3stP@ss01 |
    | new_pass     | T3stP@ss02 |
    And I press "Save changes"
    And I follow "Log Out"
    And I follow "Log In"
    And I fill in the following:
      | username | TestUser01 |
      | password | T3stP@ss02 |
    And press "Log in"
    Then I should see "Logged in as TestUser01"
    And I should see "Log Out"

  Scenario: A user cannot change their password with an incorrect current password
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestUser01  | testuser01@example.org  | T3stP@ss01 | 1       |
    Given I am logged in as "testuser01@example.org"
    Given I am on "/"
    And I follow "Account"
    And I fill in the following:
    | current_pass | 123123123  |
    | new_pass     | T3stP@ss02 |
    And I press "Save changes"
    Then the ".errors" element should contain "Please check your current password."
