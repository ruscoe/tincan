<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use TinCan\db\TCData;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCUser;

require 'vendor/autoload.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $db;

    private $created_users = [];
    private $created_board_groups = [];

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

            $this->created_board_groups[] = $board_group->get_primary_key_value();
        }
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
                    }
                }
            }
        }

        // Delete users created during the test.
        foreach ($this->created_users as $email) {
            $this->delete_user($email);
        }

        // Delete board groups created during the test.
        foreach ($this->created_board_groups as $board_group_id) {
            $this->delete_board_group($board_group_id);
        }
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
}
