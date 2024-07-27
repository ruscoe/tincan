Feature: Admin
  In order to maintain and operate the forum
  As an admin user
  I need to be able to perform administration tasks

  Scenario: A non-admin user attempts to access the admin section
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  When I am on "/admin/"
  Then the ".section-header" element should contain "Log In"

  Scenario: An admin user logs into their account.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/"
    And I follow "Administration"
    Then the "h1" element should contain "Admin Forum Settings"

  Scenario: An admin user creates a new board group.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Board Groups"
    And I follow "New Board Group"
    And I fill in the following:
    | board_group_name | Test Board Group 01 |
    And I press "Add Board Group"
    Then the "h1" element should contain "Admin Board Groups"
    And I should see "Test Board Group 01"

  Scenario: An admin user edits a board group.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Board Groups"
    And I follow "Edit" in the row containing "Test Board Group 01"
    Then the "board_group_name" field should contain "Test Board Group 01"
    When I fill in the following:
    | board_group_name | Edited Test Board Group 01 |
    And I press "Update Board Group"
    Then the "h1" element should contain "Admin Board Groups"
    And I should see "Edited Test Board Group 01"

  Scenario: An admin user deletes a board group and its boards
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Board Groups"
    And I follow "Delete" in the row containing "Test Board Group 01"
    And I press "Delete Board Group"
    Then the "h1" element should contain "Admin Board Groups"
    And I should not see "Test Board Group 01"

  Scenario: An admin user deletes a board group and moves its boards
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name        |
    | Test Source Board Group |
    | Test Target Board Group |
    Given boards exist:
    | board_name          | board_group_name        |
    | Test Moved Board 01 | Test Source Board Group |
    | Test Moved Board 02 | Test Source Board Group |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Board Groups"
    And I follow "Delete" in the row containing "Test Source Board Group"
    Then I should see "This board group contains 2 board(s)."
    When I select "move" from "board_fate"
    And I select "Test Target Board Group" from "move_to_board_group_id"
    And I press "Delete Board Group"
    And I follow "Test Target Board Group"
    Then I should see "Test Moved Board 01"
    And I should see "Test Moved Board 02"

  Scenario: An admin user deletes a board group that doesn't exist
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Board Groups"
    And I follow "Delete" in the row containing "Test Board Group 01"
    And I fill hidden field "board_group_id" with "999999"
    And I press "Delete Board Group"
    Then the ".errors" element should contain "Board group not found."

  Scenario: An admin user creates a new board.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Boards"
    And I follow "New Board"
    And I fill in the following:
    | board_name | Test Board 01 |
    And I select "Test Board Group 01" from "board_group_id"
    And I press "Add Board"
    Then the "h1" element should contain "Admin Boards"
    And I should see "Test Board 01"

  Scenario: An admin user edits a board.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given boards exist:
    | board_name    | board_group_name    |
    | Test Board 01 | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Boards"
    And I follow "Edit" in the row containing "Test Board Group 01"
    And I fill in the following:
    | board_name | Edited Test Board 01 |
    And I press "Update Board"
    Then the "h1" element should contain "Admin Boards"
    And I should see "Edited Test Board 01"

  Scenario: An admin user deletes a board and its threads
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    | TestUser01  | testuser01@example.org  | T3stP@ss01 | 1       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given boards exist:
    | board_name    | board_group_name    |
    | Test Board 01 | Test Board Group 01 |
    Given threads exist:
    | thread_title   | created_by_user        | board_name    |
    | Test Thread 01 | testuser01@example.org | Test Board 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Boards"
    And I follow "Delete" in the row containing "Test Board 01"
    And I press "Delete Board"
    Then the "h1" element should contain "Admin Boards"
    And I should not see "Test Board 01"
    When I follow "Threads"
    Then I should not see "Test Thread 01"

  Scenario: An admin user deletes a board and moves its threads
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    | TestUser01  | testuser01@example.org  | T3stP@ss01 | 1       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given boards exist:
    | board_name        | board_group_name    |
    | Test Source Board | Test Board Group 01 |
    | Test Target Board | Test Board Group 01 |
    Given threads exist:
    | thread_title      | created_by_user        | board_name        |
    | Test Moved Thread | testuser01@example.org | Test Source Board |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Boards"
    And I follow "Delete" in the row containing "Test Source Board"
    Then I should see "This board contains 1 thread(s)."
    When I select "move" from "thread_fate"
    And I select "Test Target Board" from "move_to_board_id"
    And I press "Delete Board"
    And I follow "Test Target Board"
    Then I should see "Test Moved Thread"

  Scenario: An admin user deletes a board that doesn't exist
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
    Given board groups exist:
    | board_group_name    |
    | Test Board Group 01 |
    Given boards exist:
    | board_name    | board_group_name    |
    | Test Board 01 | Test Board Group 01 |
    Given I am logged in as "testadmin01@example.org"
    When I am on "/admin/"
    And I follow "Boards"
    And I follow "Delete" in the row containing "Test Board 01"
    And I fill hidden field "board_id" with "999999"
    And I press "Delete Board"
    Then the ".errors" element should contain "Board not found."
