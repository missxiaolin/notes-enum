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

abstract class Enum
{
    public static $_instance;

//    public $_adapter = 'memory';
//
//    public $_expire = 3600;

    public function __construct()
    {

    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (isset(static::$_instance) && static::$_instance instanceof Enum) {
            return static::$_instance;
        }

        return static::$_instance = new static();
    }

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
        $message = '';

        if (isset($this->$name)) {
            return isset($this->$name[$code]) ? $this->$name[$code] : '';
        }
        // 建立反射类
        $class = new \ReflectionClass(static::class);
        $properties = $class->getProperties();
        foreach ($properties as $item) {
            if (strpos($item->getName(),'ENUM_') === 0 && $item->getValue() == $code) {
                $message = $item->getDocComment();
            }
        }
        $info = DocParserFactory::getInstance()->parse($message);

        if ($info[ucfirst($name)]) {
            return '';
        }
        return $info[ucfirst($name)];

    }
}