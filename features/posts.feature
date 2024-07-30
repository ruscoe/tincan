Feature: Posts
  In order for discussions to happen
  Boards must contain posts

  Scenario: A forum user views a post
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  When I am on "/"
  And I follow "Test Board 01"
  Then the ".thread-preview" element should contain "Test Thread 01"
  And the ".last-post-date" element should contain "TestUser01"
  When I follow "Test Thread 01"
  Then the ".section-header" element should contain "Test Thread 01"
  And the ".post-user" element should contain "TestUser01"
  And the ".post-content" element should contain "Lorem ipsum dolor sit amet, consectetur adipiscing elit."

  Scenario: A forum user creates a new thread
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "New thread"
  And I fill in the following:
  | thread_title | Test Thread 01                                           |
  | post_content | Lorem ipsum dolor sit amet, consectetur adipiscing elit. |
  And I press "Submit thread"
  Then the ".section-header" element should contain "Test Thread 01"
  And the ".post-user" element should contain "TestUser01"
  And the ".post-content" element should contain "Lorem ipsum dolor sit amet, consectetur adipiscing elit."

  Scenario: A forum user cannot create a new thread with no title
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "New thread"
  And I fill in the following:
  | thread_title |                                                          |
  | post_content | Lorem ipsum dolor sit amet, consectetur adipiscing elit. |
  And I press "Submit thread"
  Then the ".errors" element should contain "Please enter a longer title."

Scenario: A forum user cannot create a new thread with no content
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "New thread"
  And I fill in the following:
  | thread_title | Test Thread 01 |
  | post_content |                |
  And I press "Submit thread"
  Then the ".errors" element should contain "Please enter a longer post."

  Scenario: A forum user deletes their thread
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "Test Thread 01"
  And I follow "Delete thread"
  And I press "Delete thread"
  And I follow "Return to board"
  Then the ".section-header" element should contain "Test Board 01"
  And I should not see "Test Thread 01"

  Scenario: A forum user edits their post
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "Test Thread 01"
  And I follow "Edit"
  And I fill in the following:
  | post_content | Nullam euismod faucibus ipsum, a porttitor odio eleifend eget. |
  And I press "Save changes"
  Then the ".post-content" element should contain "Nullam euismod faucibus ipsum, a porttitor odio eleifend eget."

  Scenario: A forum user cannot edit another user's post
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  | TestUser02 | testuser02@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  Given I am logged in as "testuser02@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "Test Thread 01"
  Then I should not see "Edit"

  Scenario: A forum user replies to a thread
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "Test Thread 01"
  And I fill in the following:
  | post_content | Consectetur eum veniam consequuntur dolorum. |
  And I press "Submit reply"
  Then I should see "Consectetur eum veniam consequuntur dolorum."

  Scenario: A forum user cannot reply to a thread with an empty post
  Given users exist:
  | username   | email                  | password   | role_id |
  | TestUser01 | testuser01@example.org | T3stP@ss01 | 1       |
  Given board groups exist:
  | board_group_name    |
  | Test Board Group 01 |
  Given boards exist:
  | board_name    | board_group_name    |
  | Test Board 01 | Test Board Group 01 |
  Given threads exist:
  | thread_title   | created_by_user        | board_name    |
  | Test Thread 01 | testuser01@example.org | Test Board 01 |
  Given posts exist:
  | content                                                  | created_by_user        | thread_title   |
  | Lorem ipsum dolor sit amet, consectetur adipiscing elit. | testuser01@example.org | Test Thread 01 |
  Given I am logged in as "testuser01@example.org"
  When I am on "/"
  And I follow "Test Board 01"
  And I follow "Test Thread 01"
  And I fill in the following:
  | post_content | |
  And I press "Submit reply"
  Then the ".errors" element should contain "Please enter a longer reply."
