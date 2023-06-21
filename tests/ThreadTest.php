<?php

use PHPUnit\Framework\TestCase;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;

class ThreadTest extends TestCase
{
    public function testPostCanBeDeleted()
    {
        $post = new TCPost();
        $post->post_id = 1;

        $thread = new TCThread();
        $thread->thread_id = 1;
        $thread->first_post_id = $post->post_id;

        // The first post in a thread cannot be deleted.
        $this->assertFalse($thread->post_can_be_deleted($post));

        $thread->first_post_id = 2;

        $this->assertTrue($thread->post_can_be_deleted($post));
    }
}
