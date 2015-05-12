<?php

namespace Mvc\Controller;

use Mvc\Model\Model;
use Mvc\View\View;

class Controller {

    private $Model = null;

    public function __construct()
    {
        $this->Model = new Model();
    }

    public function createDeck()
    {
        if (!$_SESSION) {
            $deck = $this->Model->createDeck();
            $a[1] = array_pop($deck);
            $a[2] = array_pop($deck);
            $b[1] = array_pop($deck);
            $b[2] = array_pop($deck);
            $_SESSION['a']=$a;
            $_SESSION['b']=$b;
            $_SESSION['deck']=$deck;
        }
        return View::render(array('status' => $_SESSION));
    }

    public function game_Reset()
    {
        session_destroy();
    }

    public function game_Sum(){
        $status = $this->Model->game_Sum($_SESSION);
        return View::render(array('status' => $status));
    }

    public function game_Hit()
    {
        $status = $this->Model->game_Hit($_SESSION[$_GET['i']], $_SESSION['deck']);
        $_SESSION[$_GET['i']]=$status['data'];
        $_SESSION['deck']=$status['deck'];
        return View::render(array('status' => $_SESSION));
    }

    public function game_Fold()
    {
        $status = $this->Model->game_Fold($_SESSION, $this->Model->game_Sum($_SESSION)['sumValue']);

        return View::render(array('status' => $status));
        $this->game_Reset();
    }
}