<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use TinCan\db\TCData;
use TinCan\objects\TCUser;

require 'vendor/autoload.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $db;

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

    /** @AfterScenario */
    public function after($event)
    {
        $scenario = $event->getScenario();
        $scenario_title = $scenario->getTitle();

        // Delete user created during the test.
        $steps = $scenario->getSteps();

        foreach ($steps as $step) {
            if ($step->getText() == 'I fill in the following:') {
                $table = $step->getArguments()[0]->getTable();
                foreach ($table as $row) {
                    if ($row[0] == 'email') {
                        $this->delete_user($row[1]);
                    }
                }
            }
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
}
