<?php

namespace Traits;


trait Singleton
{
    private static $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
}