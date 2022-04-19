<?php

require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCPost;
use TinCan\TCRole;
use TinCan\TCUser;

class ModeratorTest extends TestCase
{
  private $role_mod;

  protected function setUp(): void
  {
    $this->role_mod = new TCRole((object) [
      'role_name' => 'Moderator',
      'allowed_actions' => 'create-post,create-thread,edit-any-post,edit-any-thread,delete-any-post,delete-any-thread',
    ]);

    parent::setUp();
  }

  public function testModeratorRole()
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

  public function testModeratorCanEditAnyPost()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_mod;

    $post = new TCPost();
    $post->post_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $post->user_id = 2;

    $this->assertTrue($user->can_edit_post($post));
  }

  public function testModeratorCanDeleteAnyPost()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_mod;

    $post = new TCPost();
    $post->post_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $post->user_id = 2;

    $this->assertTrue($user->can_delete_post($post));
  }

  public function testModeratorCanEditAnyThread()
  {
  }

  public function testModeratorCanDeleteAnyThread()
  {
  }
}
