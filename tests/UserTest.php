<?php

require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCPost;
use TinCan\TCRole;
use TinCan\TCUser;

class UserTest extends TestCase
{
  private $role_user;

  protected function setUp(): void
  {
    $this->role_user = new TCRole((object) [
      'role_name' => 'User',
      'allowed_actions' => 'create-post,create-thread',
    ]);

    parent::setUp();
  }

  public function testUserRole()
  {
    $user = new TCUser();
    $user->role = $this->role_user;

    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_EDIT_ANY_POST));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_EDIT_ANY_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_DELETE_ANY_POST));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_DELETE_ANY_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
  }

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

    // Test password at minimum length.
    $password = '';
    for ($i = 0; $i < TCUser::MIN_PASSWORD_LENGTH; ++$i) {
      $password .= 'a';
    }

    $this->assertTrue($user->validate_password($password));

    // Test password below minimum length.
    $password = '';
    for ($i = 0; $i < (TCUser::MIN_PASSWORD_LENGTH - 1); ++$i) {
      $password .= 'a';
    }

    $this->assertFalse($user->validate_password($password));

    // Test password above minimum length.
    $password = '';
    for ($i = 0; $i < (TCUser::MIN_PASSWORD_LENGTH + 1); ++$i) {
      $password .= 'a';
    }

    $this->assertTrue($user->validate_password($password));
  }

  public function testPasswordHash()
  {
    $user = new TCUser();

    $password = 'hK7Ek$DCKddq&SM$';

    $password_hash = $user->get_password_hash($password);

    $this->assertFalse(empty($password_hash));

    $this->assertTrue($user->verify_password_hash($password, $password_hash));
  }

  public function testUserCanEditOwnPost()
  {
    $post = new TCPost();
    $post->post_id = 99;
    $post->user_id = 1;

    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_user;

    $this->assertTrue($user->can_edit_post($post));
  }

  public function testUserCanEditAnyPost()
  {
    $post = new TCPost();
    $post->post_id = 99;
    $post->user_id = 2;

    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_user;

    $this->assertFalse($user->can_edit_post($post));
  }
}
