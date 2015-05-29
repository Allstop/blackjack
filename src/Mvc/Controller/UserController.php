<?php

namespace Mvc\Controller;

use Mvc\Model\UserModel;
use Mvc\View\View;
use Mvc\Core\Data;

class UserController
{
    // 共用的物件
    private $Model = NULL;
    public static $data = null;
    // 初始化要執行的動作以及物件
    public function __construct()
    {
        $this->Model = UserModel::init();
        self::$data = new Data();
    }
    //登入檢查
    public function loginCheck()
    {
        if (!$_SESSION['name']) {
            $status = $this->Model->loginCheck(self::$data->getData());
            if ($status == false ) {
                return View::render(array('status' => $status));
            } else {
                $_SESSION['name'] = $status['name'];
                $_SESSION['money'] = $status['money'];
                return View::render(array('status' => $status));
            }
        } else {
            return View::render(array('status' => $_SESSION));
        }
    }
    //登出
    public function logout()
    {
        session_destroy();
    }
    //建立
    public function create()
    {
        $status = $this->Model->create($_SESSION);
        return View::render(array('status' => $status));
    }
    //建立檢查
    public function createCheck()
    {

        $status = $this->Model->createCheck($_SESSION['name']);
        if (empty($_SESSION)) {
            return View::render(array('status' => 'error'));
        } elseif ($status == false) {
            return View::render(array('status' => false));
        }else {
            return View::render(array('status' => 'success'));
        }
    }
}