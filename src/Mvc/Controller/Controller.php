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
        if (!$_SESSION['name']) {
            return View::render(array('status' => false));
        } else {
            if (!$_SESSION['show']) {
                if ($_SESSION['hide']['deck']) {
                    $status = $this->Model->game_Deal($_SESSION['hide']['deck']);
                } else {
                    $deck = $this->Model->game_Deck();
                    $status = $this->Model->game_Deal($deck);
                }
                $_SESSION['show']=$status['show'];
                $_SESSION['hide']=$status['hide'];
            }
            return View::render(array('status' => $_SESSION['show']));
        }
    }

    public function game_Sum(){
        $status = $this->Model->game_Sum($_POST);
        return View::render(array('status' => $status));
    }

    public function game_Insurance(){

        if ($_SESSION['show']['a']['num']=11) {
            if ($_SESSION['hide']['a']['num']=21) {
                return View::render(array('status' => 1));
            } else {
                return View::render(array('status' => 0));
            }
        } else {
            return View::render(array('status' => false));
        }

    }

    public function game_Spilt(){
        $status = $this->Model->game_Spilt($_SESSION['show']['b']);
        $_SESSION['show']['b']=$status['b'];
        $_SESSION['show']['b1']=$status['b1'];
        $_SESSION['show']['b']['sum']=$this->Model->game_Sum($status['b'])['sum'];

        $bb=$this->Model->game_Hit($_SESSION['show']['b'], $_SESSION['hide']['deck']);
        $_SESSION['show']['b'][$bb['num']]= $bb['data'];
        $_SESSION['show']['b']['num']=$this->Model->game_Sum($_SESSION['show']['b'])['num'];
        $_SESSION['show']['b']['sum']=$this->Model->game_Sum($_SESSION['show']['b'])['sum'];

        return View::render(array('status' => $_SESSION['show']));
    }

    public function game_Hit()
    {
        $status = $this->Model->game_Hit($_SESSION['show'][$_GET['i']], $_SESSION['hide']['deck']);
        $_SESSION['show'][$_GET['i']][$status['num']]= $status['data'];
        $_SESSION['show'][$_GET['i']]['num']=$this->Model->game_Sum($_SESSION['show'][$_GET['i']])['num'];
        $_SESSION['show'][$_GET['i']]['sum']=$this->Model->game_Sum($_SESSION['show'][$_GET['i']])['sum'];
        $_SESSION['hide']['deck']=$status['deck'];
        return View::render(array('status' => array($_GET['i']=>$_SESSION['show'][$_GET['i']])));
    }

    public function game_Stand()
    {
        if ($_SESSION['hide']['a']) {
            $_SESSION['show']['a']=$_SESSION['hide']['a'];
            unset($_SESSION['hide']['a']);
        }
        if ($_SESSION['show']) {
            $status = $this->Model->game_Stand($_SESSION['show'], $_SESSION['hide']['deck']);
            $_SESSION['hide']['deck']=$status['deck'];
            unset($_SESSION['show']);
            return View::render(array('status' => $status['show']));
        }
        return View::render(array('status' => false));
    }
    public function game_Wash()
    {
        unset($_SESSION['hide']);
        unset($_SESSION['show']);
    }

}