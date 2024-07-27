<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use TinCan\db\TCData;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCPost;
use TinCan\objects\TCSession;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;

require 'vendor/autoload.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * @var TCData
     * The database object.
     */
    private $db;

    /**
     * @var array
     * An array of user emails created during the test.
     */
    private $created_users = [];

    /**
     * @var array
     * An associative array of board group IDs to names created during the test.
     */
    private $created_board_groups = [];

    /**
     * @var array
     * An associative array of board IDs to names created during the test.
     */
    private $created_boards = [];

    /**
     * @var array
     * An associative array of thead IDs to names created during the test.
     */
    private $created_threads = [];

    /**
     * @var array
     * An array of post IDs created during the test.
     */
    private $created_posts = [];

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->db = new TCData();
    }

    /**
     * @Given users exist:
     */
    public function given_users_exist(TableNode $table)
    {
        foreach ($table as $row) {
            $user = new TCUser();
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->password = $user->get_password_hash($row['password']);
            $user->role_id = $row['role_id'];
            $user->suspended = 0;
            $user->created_time = time();
            $user->updated_time = time();

            $this->db->save_object($user);

            $this->created_users[] = $row['email'];
        }
    }

    /**
     * @Given board groups exist:
     */
    public function given_board_groups_exist(TableNode $table)
    {
        foreach ($table as $row) {
            $board_group = new TCBoardGroup();
            $board_group->board_group_name = $row['board_group_name'];
            $board_group->created_time = time();
            $board_group->updated_time = time();

            $this->db->save_object($board_group);

            $this->created_board_groups[$board_group->get_primary_key_value()] = $row['board_group_name'];
        }
    }

    /**
     * @Given boards exist:
     */
    public function given_boards_exist(TableNode $table)
    {
        foreach ($table as $row) {
            $board = new TCBoard();
            $board->board_name = $row['board_name'];
            $board->created_time = time();
            $board->updated_time = time();

            // Set the board group ID from the created board groups.
            foreach ($this->created_board_groups as $board_group_id => $name) {
                if ($name == $row['board_group_name']) {
                    $board->board_group_id = $board_group_id;
                }
            }

            $this->db->save_object($board);

            $this->created_boards[$board->get_primary_key_value()] = $row['board_name'];
        }
    }

    /**
     * @Given threads exist:
     */
    public function given_threads_exist(TableNode $table)
    {
        foreach ($table as $row) {
            $thread = new TCThread();
            $thread->thread_title = $row['thread_title'];
            $thread->created_time = time();
            $thread->updated_time = time();
            // No posts yet.
            $thread->first_post_id = 0;

            // Set the thread's user ID.
            $user = $this->get_user($row['created_by_user']);
            if (!empty($user)) {
                $thread->created_by_user = $user->user_id;
                $thread->updated_by_user = $user->user_id;
            }

            // Set the board ID from the created boards.
            foreach ($this->created_boards as $board_id => $name) {
                if ($name == $row['board_name']) {
                    $thread->board_id = $board_id;
                }
            }

            $this->db->save_object($thread);

            $this->created_threads[$thread->get_primary_key_value()] = $row['thread_title'];
        }
    }

    /**
     * @Given posts exist:
     */
    public function given_posts_exist(TableNode $table)
    {
        foreach ($table as $row) {
            $post = new TCPost();
            $post->content = $row['content'];
            $post->created_time = time();
            $post->updated_time = time();

            // Set the post's user ID.
            $user = $this->get_user($row['created_by_user']);
            if (!empty($user)) {
                $post->user_id = $user->user_id;
                $post->updated_by_user = $user->user_id;
            }

            // Set the thread ID from the created threads.
            foreach ($this->created_threads as $thread_id => $name) {
                if ($name == $row['thread_title']) {
                    $post->thread_id = $thread_id;
                }
            }

            $this->db->save_object($post);

            $this->created_posts[] = $post->get_primary_key_value();
        }
    }

    /**
     * @Given I am logged in as :email
     */
    public function given_i_am_logged_in_as($email)
    {
        $user = $this->get_user($email);

        if (!empty($user)) {
            // Create a new session.
            $session = new TCSession();
            $session->user_id = $user->user_id;
            $session->hash = $session->generate_random_hash();
            $session->created_time = time();
            // Set the session expiration time to 30 days.
            $session->expiration_time = time() + (60 * 60 * 24 * 30);

            $this->db->save_object($session);

            // Set the session cookie.
            $this->getSession()->setcookie('session', $session->get_hash(), time() + (60 * 60 * 24 * 30), '/');
        }
    }

    /**
     * @When /^I follow "([^"]*)" in the row containing "([^"]*)"$/
     */
    public function iFollowInTheRowContaining($link, $text)
    {
        $row = $this->getSession()->getPage()->find('css', sprintf('table tr:contains("%s")', $text));
        if (!$row) {
            throw new \Exception(sprintf('Cannot find table row containing the text "%s"', $text));
        }

        $row->clickLink($link);
    }

    /** @AfterScenario */
    public function after($event)
    {
        $scenario = $event->getScenario();
        $scenario_title = $scenario->getTitle();

        // Track users created during the test.
        $steps = $scenario->getSteps();

        foreach ($steps as $step) {
            if ($step->getText() == 'I fill in the following:') {
                $table = $step->getArguments()[0]->getTable();
                foreach ($table as $row) {
                    if ($row[0] == 'email') {
                        $this->created_users[] = $row[1];
                    } elseif ($row[0] == 'thread_title') {
                        $thread = $this->get_thread($row[1]);
                        if (!empty($thread)) {
                            $this->created_threads[$thread->get_primary_key_value()] = $row[1];
                        }
                    } elseif ($row[0] == 'board_group_name') {
                        $board_group = $this->get_board_group($row[1]);
                        if (!empty($board_group)) {
                            $this->created_board_groups[$board_group->get_primary_key_value()] = $row[1];
                        }
                    }
                }
            }
        }

        // Delete users created during the test.
        foreach ($this->created_users as $email) {
            $this->delete_user($email);
        }

        // Delete board groups created during the test.
        foreach ($this->created_board_groups as $board_group_id => $name) {
            $this->delete_board_group($board_group_id);
        }

        // Delete boards created during the test.
        foreach ($this->created_boards as $board_id => $name) {
            $this->delete_board($board_id);
        }

        // Delete threads created during the test.
        foreach ($this->created_threads as $thread_id => $name) {
            $this->delete_thread($thread_id);
        }

        // Delete posts created during the test.
        foreach ($this->created_posts as $post_id) {
            $this->delete_post($post_id);
        }

        // Reset the created arrays.
        $this->created_users = [];
        $this->created_board_groups = [];
        $this->created_boards = [];
        $this->created_threads = [];
        $this->created_posts = [];
    }

    /**
     * Gets a user from the database.
     *
     * @param string $email
     *   The email address of the user.
     *
     * @return TCUser
     */
    private function get_user($email)
    {
        $conditions = [
            ['field' => 'email', 'value' => $email],
        ];
        $results = $this->db->load_objects(new TCUser(), [], $conditions);

        return reset($results);
    }

    /**
     * Gets a thread from the database.
     *
     * @param string $thread_title
     *   The title of the thread.
     *
     * @return TCThread
     */
    private function get_thread($thread_title)
    {
        $conditions = [
            ['field' => 'thread_title', 'value' => $thread_title],
        ];
        $results = $this->db->load_objects(new TCThread(), [], $conditions);

        return reset($results);
    }

    /**
     * Gets a board group from the database.
     *
     * @param string $board_group_name
     *   The name of the board group.
     *
     * @return TCBoardGroup
     */
    private function get_board_group($board_group_name)
    {
        $conditions = [
            ['field' => 'board_group_name', 'value' => $board_group_name],
        ];
        $results = $this->db->load_objects(new TCBoardGroup(), [], $conditions);

        return reset($results);
    }

    /**
     * Deletes a user from the database.
     */
    private function delete_user($email)
    {
        $conditions = [
            ['field' => 'email', 'value' => $email],
        ];
        $results = $this->db->load_objects(new TCUser(), [], $conditions);
        $user = reset($results);

        if (!empty($user)) {
            $this->db->delete_object(new TCUser(), $user->user_id);
        }
    }

    /**
     * Deletes a board group from the database.
     */
    private function delete_board_group($board_group_id)
    {
        $this->db->delete_object(new TCBoardGroup(), $board_group_id);
    }

    /**
     * Deletes a board from the database.
     */
    private function delete_board($board_id)
    {
        $this->db->delete_object(new TCBoard(), $board_id);
    }

    /**
     * Deletes a thread from the database.
     */
    private function delete_thread($thread_id)
    {
        $this->db->delete_object(new TCThread(), $thread_id);
    }
    /**
     * Looks for a table, then looks for a row that contains the given text.
     * Once it finds the right row, it clicks a link in that row.
     *
     * Really handy when you have a generic "Edit" link on each row of
     * a table, and you want to click a specific one (e.g. the "Edit" link
     * in the row that contains "Item #2")
     *
     * @When /^I click on "([^"]*)" on the row containing "([^"]*)"$/
     */
    public function iClickOnOnTheRowContaining($linkName, $rowText)
    {
        /** @var $row \Behat\Mink\Element\NodeElement */
        $row = $this->getPage()->find('css', sprintf('table tr:contains("%s")', $rowText));
        if (!$row) {
            throw new \Exception(sprintf('Cannot find any row on the page containing the text "%s"', $rowText));
        }

        $row->clickLink($linkName);
    }
    /**
     * Deletes a post from the database.
     */
    private function delete_post($post_id)
    {
        $this->db->delete_object(new TCPost(), $post_id);
    }
}
