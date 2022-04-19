<?php

require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCPost;
use TinCan\TCRole;
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

  public function testAdministratorRole()
  {
    $role = new TCRole();
    $role->role_name = 'Administrator';
    $role->allowed_actions = 'create-post,create-thread,edit-any-post,edit-any-thread,delete-any-post,delete-any-thread,access-admin';

    $user = new TCUser();
    $user->role = $role;

    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
  }

  public function testModRole()
  {
    $role = new TCRole();
    $role->role_name = 'Moderator';
    $role->allowed_actions = 'create-post,create-thread,edit-any-post,edit-any-thread,delete-any-post,delete-any-thread';

    $user = new TCUser();
    $user->role = $role;

    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
  }

  public function testUserRole()
  {
    $role = new TCRole();
    $role->role_name = 'User';
    $role->allowed_actions = 'create-post,create-thread';

    $user = new TCUser();
    $user->role = $role;

    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_EDIT_ANY_POST));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_EDIT_ANY_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_DELETE_ANY_POST));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_DELETE_ANY_THREAD));
    $this->assertFalse($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
  }

  public function testUserCanEditOwnPost()
  {
    $post = new TCPost();
    $post->post_id = 99;
    $post->user_id = 1;

    $user = new TCUser();
    $user->user_id = 1;

    $this->assertTrue($user->can_edit_post($post));
  }

  public function testUserCanEditOtherUserPost()
  {
    $post = new TCPost();
    $post->post_id = 99;
    $post->user_id = 2;

    $user = new TCUser();
    $user->user_id = 1;

    $this->assertFalse($user->can_edit_post($post));
  }
}
