<?php

require_once TC_BASE_PATH.'/includes/include-db.php';
require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCData;

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
