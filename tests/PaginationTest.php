<?php

use PHPUnit\Framework\TestCase;
use TinCan\template\TCPagination;

class PaginationTest extends TestCase
{
    public function testCalculateTotalPages()
    {
        // Test even results.
        $total_pages = TCPagination::calculate_total_pages(100, 10);
        $this->assertEquals(10, $total_pages);

        // Test rounding up.
        $total_pages = TCPagination::calculate_total_pages(144, 10);
        $this->assertEquals(15, $total_pages);
    }
}
