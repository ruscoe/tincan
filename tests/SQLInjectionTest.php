<?php

use PHPUnit\Framework\TestCase;
use TinCan\db\TCData;

class SQLInjectionTest extends TestCase
{
    public function testLoadUser()
    {
        $db = new TCData();

        // Attempt to load all users through SQL injection.
        $injection = "'' or 1=1;--";

        $result = $db->load_user($injection);

        $this->assertEmpty($result);
    }
}
