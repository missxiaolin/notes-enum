<?php

namespace Lin\Enum;

// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------


use Lin\Enum\Code\DocParserFactory;
use Xin\Traits\Common\InstanceTrait;

abstract class Enum
{
    use InstanceTrait;

    public static $_instance;

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return static::getInstance()->$method(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     * @throws EnumException
     */
    public function __call($name, $arguments)
    {
        $code = $arguments[0];
        $name = strtolower(substr($name, 3));

        $docs = [];

        if (isset($this->$name)) {
            return isset($this->$name[$code]) ? $this->$name[$code] : '';
        }

        // 建立反射类
        $class = new \ReflectionClass(static::class);
        $properties = $class->getProperties();
        foreach ($properties as $item) {
            if (strpos($item->getName(), 'ENUM_') === 0) {
                $docs[$item->getValue()] = $item->getDocComment();
            }
        }

        // 缓存全部枚举信息
        foreach ($docs as $id => $doc) {
            $info = DocParserFactory::getInstance()->parse($doc);
            foreach ($info as $k => $v) {
                $k = strtolower($k);
                $this->$k[$id] = $v;
            }
        }

        return isset($this->$name[$code]) ? $this->$name[$code] : '';
    }
}