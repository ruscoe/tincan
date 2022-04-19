<?php

require TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCUser;

class UserTest extends TestCase
{
  public function testUsername()
  {
    $user = new TCUser();

    // Test username at minimum length.
    $username = '';
    for ($i = 0; $i < TCUser::MIN_USERNAME_LENGTH; ++$i) {
      $username .= 'a';
    }

    $this->assertTrue($user->validate_username($username));

    // Test username below minimum length.
    $username = '';
    for ($i = 0; $i < (TCUser::MIN_USERNAME_LENGTH - 1); ++$i) {
      $username .= 'a';
    }

    $this->assertFalse($user->validate_username($username));

    // Test username above minimum length.
    $username = '';
    for ($i = 0; $i < (TCUser::MIN_USERNAME_LENGTH + 1); ++$i) {
      $username .= 'a';
    }

    $this->assertTrue($user->validate_username($username));
  }

  public function testPassword()
  {
    $user = new TCUser();

    $password = 'hK7Ek$DCKddq&SM$';

    $password_hash = $user->get_password_hash($password);

    $this->assertFalse(empty($password_hash));

    $this->assertTrue($user->verify_password_hash($password, $password_hash));
  }
}
