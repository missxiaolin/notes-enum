<?php
namespace Lin\Enum\Code;

class DocParserFactory
{
    private static $p;

    private function DocParserFactory()
    {
    }

    public static function getInstance()
    {
        if (self::$p == null) {
            self::$p = new DocParser ();
        }
        return self::$p;
    }
}