<?php

require_once TC_BASE_PATH.'/includes/include-objects.php';

use PHPUnit\Framework\TestCase;
use TinCan\TCRole;
use TinCan\TCUser;

class UserRoleTest extends TestCase
{
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
}
