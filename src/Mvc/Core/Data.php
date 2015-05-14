<?php

namespace Mvc\Core;

class Data {

    public function getData()
    {
        foreach ($_POST as $key => $value)
        {
            $_POST[$key] = trim($value);
        }
        $userData = array();
        if (isset($_POST['id'])) {
            $userData['id'] = $_POST['id'];
        }
        if (isset($_POST['name'])) {
            $userData['name'] = $_POST['name'];
        }
        if (isset($_POST['password'])) {
            $userData['password'] = $_POST['password'];
        }
        if (isset($_POST['money'])) {
            $userData['money'] = $_POST['money'];
        }
        return $userData;
    }

}