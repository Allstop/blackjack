<?php

namespace Mvc\Core;

class db {
    private static $_conn = null;

    static function getInstance($filename = null, $path = null){
        if(self::$_conn === null){
            self::$_conn = array();
            if (! $path) {
                $path = dirname(dirname(dirname(__DIR__))).'/config';
            }
            if (! $filename) {
                $filename = 'config.php';
            }
            self::$_conn = require(implode('/', array($path, $filename)));
            self::$_conn = new \PDO(self::$_conn['db']['dsn'], self::$_conn['db']['user'], self::$_conn['db']['pwd']);
        }
        return self::$_conn;
    }
}
