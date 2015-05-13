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
        if (!$_SESSION) {
            $status = $this->Model->game_Deal();
            $_SESSION['f']=$status['f'];
            $_SESSION['a']=$status['a'];
            $_SESSION['a']['num']=$this->Model->game_Sum($_SESSION['a'])['num'];
            $_SESSION['a']['sum']=$this->Model->game_Sum($_SESSION['a'])['sumValue'];
            $_SESSION['b']=$status['b'];
            $_SESSION['b']['num']=$this->Model->game_Sum($_SESSION['b'])['num'];
            $_SESSION['b']['sum']=$this->Model->game_Sum($_SESSION['b'])['sumValue'];
            $_SESSION['deck']=$status['deck'];
        }
        return View::render(array('status' => array(a=>$_SESSION['a'], b=>$_SESSION['b'])));

    }

//    public function game_Sum(){
//        $status = $this->Model->game_Sum($_POST);
//        return View::render(array('status' => $status));
//    }

    public function game_Spilt(){
        $status = $this->Model->game_Spilt($_SESSION['b']);
        return View::render(array('status' => $status));
    }
    public function game_Hit()
    {
        $status = $this->Model->game_Hit($_SESSION[$_GET['i']], $_SESSION['deck']);
        $_SESSION[$_GET['i']][$status['num']]= $status['data'];
        $_SESSION[$_GET['i']]['num']=$this->Model->game_Sum($_SESSION[$_GET['i']])['num'];
        $_SESSION[$_GET['i']]['sum']=$this->Model->game_Sum($_SESSION[$_GET['i']])['sumValue'];
        $_SESSION['deck']=$status['deck'];
        return View::render(array('status' => array(a=>$_SESSION['a'], b=>$_SESSION['b'])));
    }

    public function game_Stand()
    {
//        $status = $this->Model->game_Stand($_SESSION, $this->Model->game_Sum($_SESSION)['sumValue']);
//        return View::render(array('status' => $status));
        session_destroy();
    }
}