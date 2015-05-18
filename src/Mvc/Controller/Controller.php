<?php

namespace Mvc\Controller;

use Mvc\Model\Model;
use Mvc\View\View;

class Controller {

    private $Model = null;

    public function __construct()
    {
        $this->Model = Model::init();
    }

    public function game_Deal()
    {
        if (!$_SESSION['a']) {
            $status = $this->Model->game_Deal();
            $_SESSION['f']=$status['f'];
            $_SESSION['f']['num']=$this->Model->game_Sum($_SESSION['f'])['num'];
            $_SESSION['a']=$status['a'];
            $_SESSION['a']['num']=$this->Model->game_Sum($_SESSION['a'])['num'];
            $_SESSION['a']['sum']=$this->Model->game_Sum($_SESSION['a'])['sumValue'];
            $_SESSION['b']=$status['b'];
            $_SESSION['b']['num']=$this->Model->game_Sum($_SESSION['b'])['num'];
            $_SESSION['b']['sum']=$this->Model->game_Sum($_SESSION['b'])['sumValue'];
            $_SESSION['deck']=$status['deck'];
        }
        asort($_SESSION);
        return View::render(array('status' => array_slice($_SESSION, 1, -1)));

    }

//    public function game_Sum(){
//        $status = $this->Model->game_Sum($_POST);
//        return View::render(array('status' => $status));
//    }

    public function game_Insurance(){

        if ($_SESSION['a']['num']=11) {
            if ($_SESSION['f']['num']=10) {
                return View::render(array('status' => 1));
            } else {
                return View::render(array('status' => 0));
            }
        } else {
            return View::render(array('status' => false));
        }

    }

    public function game_Spilt(){
        $status = $this->Model->game_Spilt($_SESSION['b']);
        $_SESSION['b']=$status['b'];
        $_SESSION['b1']=$status['b1'];
        $_SESSION['b']['sum']=$this->Model->game_Sum($status['b'])['sumValue'];
        $_SESSION['b1']['sum']=$this->Model->game_Sum($status['b1'])['sumValue'];
        return View::render(array('status' => array(a=>$_SESSION['a'], b=>$_SESSION['b'], b1=>$_SESSION['b1'])));
    }

    public function game_Hit()
    {
        $status = $this->Model->game_Hit($_SESSION[$_GET['i']], $_SESSION['deck']);
        $_SESSION[$_GET['i']][$status['num']]= $status['data'];
        $_SESSION[$_GET['i']]['num']=$this->Model->game_Sum($_SESSION[$_GET['i']])['num'];
        $_SESSION[$_GET['i']]['sum']=$this->Model->game_Sum($_SESSION[$_GET['i']])['sumValue'];
        $_SESSION['deck']=$status['deck'];
        return View::render(array('status' => array($_GET['i']=>$_SESSION[$_GET['i']])));
    }

    public function game_Stand()
    {

        //var_dump($_SESSION['b']);
        $status = $this->Model->game_Stand($_SESSION);
        //var_dump($status);
        session_destroy();
        return View::render(array('status' => $status));
    }
}