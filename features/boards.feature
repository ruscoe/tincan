Feature: Boards
  In order for the forum to be functional
  Board groups and boards must exist

  Scenario: A forum user views a board group
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  And I am on "/"
  Then I should see "Test Board Group 01"
  When I follow "Test Board Group 01"
  Then the ".section-header" element should contain "Test Board Group 01"
