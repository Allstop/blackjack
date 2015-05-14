<?php

namespace Mvc\Model;

use Mvc\Core\db;

class UserModel extends db
{
    private static $db = array();
    protected $status = false;
    public static $user = null;

    protected function __construct()
    {
        try {
            self::$db = db::getInstance();
            self::$db->query('set character set utf8');
            $this->status = true;
        } catch (PDOException $e) {
            $this->status = false;
            return;
        }
    }

    public static function init()
    {
        if (!static::$user) {
            static::$user = new self();
        }
        return static::$user;
    }
    //*檢查登入資料是否已存在
    public function loginCheck($gtPost)
    {
        $sql = self::$db->prepare("SELECT name,money FROM players
        where name='".$gtPost['name']."' and password='".$gtPost['password']."' "
        );
        if ($sql->execute()) {
            $sql=$sql->fetch(\PDO::FETCH_ASSOC);
            return $sql;
        } else {
            return false;
        }
    }
    //*建立使用者
    public function create($gtPost)
    {
        if ($this->status !== true) {
            return 'error in create!';
        }
        try {
            $_name = $gtPost['name'];
            $_password = $gtPost['password'];
            $sql = self::$db->prepare(
                "INSERT INTO players (name, password)
            VALUES (:name, :password)"
            );
            $sql->bindvalue(':name', $_name);
            $sql->bindvalue(':password', $_password);
            return ($sql->execute()) ? $gtPost['name'] : '失敗';
        } catch (PDOException $e) {
            return 'error in insert!';
        }
    }
    //*檢查建立資料是否已存在
    public function createCheck($name)
    {
        $sql = self::$db->query(
            "SELECT name FROM players
        where name='".$name."'"
        );
        if ($sql->execute()) {
            $sql=$sql->fetch(\PDO::FETCH_ASSOC);
            return $sql;
        }
    }
}