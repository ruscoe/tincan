Feature: Posts
  In order for users to maintain a presence on the forum
  User profiles must exist

  Scenario: A forum user views their profile
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "TestUser01"
  Then the ".section-header" element should contain "TestUser01"
