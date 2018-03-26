<?php

namespace Tests\App;

// +----------------------------------------------------------------------
// | ErrorCode.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------


use Lin\Enum\Enum;

class ErrorCode extends Enum
{
    /**
     * @Message('非法的TOKEN')
     * @Desc('需要重新登录')
     */
    public static $ENUM_INVALID_TOKEN = 700;

    /**
     * @Message('非法的用户')
     * @Desc('需要重新注册')
     * @Key('INVALID')
     */
    public static $ENUM_INVALID_USER = 701;
}
