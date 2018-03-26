<?php
namespace Tests\Test;

// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------


use PHPUnit\Framework\TestCase;
use Tests\App\ErrorCode;

class BaseTest extends TestCase
{
    public function testAscii()
    {
        $this->assertEquals(700, ErrorCode::$ENUM_INVALID_TOKEN);
    }

    public function testMessage()
    {
        $this->assertEquals('非法的TOKEN', ErrorCode::getMessage(ErrorCode::$ENUM_INVALID_TOKEN));
    }

    public function testDesc()
    {
        $this->assertEquals('需要重新登录', ErrorCode::getDesc(ErrorCode::$ENUM_INVALID_TOKEN));
    }
}
