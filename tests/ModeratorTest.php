<?php

use PHPUnit\Framework\TestCase;
use TinCan\objects\TCPost;
use TinCan\objects\TCRole;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;

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
        $user = new TCUser();
        $user->role = $this->role_mod;

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
        $user = new TCUser();
        $user->user_id = 1;
        $user->role = $this->role_mod;

        $thread = new TCThread();
        $thread->thread_id = 99;
        // Post's user ID shouldn't be the same as Moderator's user ID.
        $thread->created_by_user = 2;

        $this->assertTrue($user->can_edit_thread($thread));
    }

    public function testModeratorCanDeleteAnyThread()
    {
        $user = new TCUser();
        $user->user_id = 1;
        $user->role = $this->role_mod;

        $thread = new TCThread();
        $thread->thread_id = 99;
        // Post's user ID shouldn't be the same as Moderator's user ID.
        $thread->created_by_user = 2;

        $this->assertTrue($user->can_delete_thread($thread));
    }
}
