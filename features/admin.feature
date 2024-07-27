Feature: Admin
  In order to maintain and operate the forum
  As an admin user
  I need to be able to perform administration tasks

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

  Scenario: An admin user edits a board group.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |

  Scenario: An admin user deletes a board group.
    Given users exist:
    | username    | email                   | password   | role_id |
    | TestAdmin01 | testadmin01@example.org | T3stP@ss01 | 3       |
