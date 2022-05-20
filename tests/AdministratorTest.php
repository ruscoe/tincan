<?php

require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCPost;
use TinCan\TCRole;
use TinCan\TCThread;
use TinCan\TCUser;

class AdministratorTest extends TestCase
{
  private $role_admin;

  protected function setUp(): void
  {
    $this->role_admin = new TCRole((object) [
      'role_name' => 'Administrator',
      'allowed_actions' => 'create-post,create-thread,edit-any-post,edit-any-thread,delete-any-post,delete-any-thread,access-admin',
    ]);

    parent::setUp();
  }

  public function testAdministratorRole()
  {
    $user = new TCUser();
    $user->role = $this->role_admin;

    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_CREATE_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_EDIT_ANY_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_POST));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_DELETE_ANY_THREAD));
    $this->assertTrue($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN));
  }

  public function testAdministratorCanEditAnyPost()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_admin;

    $post = new TCPost();
    $post->post_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $post->user_id = 2;

    $this->assertTrue($user->can_edit_post($post));
  }

  public function testAdministratorCanDeleteAnyPost()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_admin;

    $post = new TCPost();
    $post->post_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $post->user_id = 2;

    $this->assertTrue($user->can_delete_post($post));
  }

  public function testAdministratorCanEditAnyThread()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_admin;

    $thread = new TCThread();
    $thread->thread_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $thread->created_by_user = 2;

    $this->assertTrue($user->can_edit_thread($thread));
  }

  public function testAdministratorCanDeleteAnyThread()
  {
    $user = new TCUser();
    $user->user_id = 1;
    $user->role = $this->role_admin;

    $thread = new TCThread();
    $thread->thread_id = 99;
    // Post's user ID shouldn't be the same as Moderator's user ID.
    $thread->created_by_user = 2;

    $this->assertTrue($user->can_delete_thread($thread));
  }
}
