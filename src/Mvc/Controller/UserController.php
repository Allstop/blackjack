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
        $_SESSION['name'] = $this->gtPost['name'];
        $_SESSION['password'] = $this->gtPost['password'];

    }
    //登入檢查
    public function loginCheck()
    {
        $status = $this->Model->loginCheck($_POST);
        return View::render(array('status' => $status));
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