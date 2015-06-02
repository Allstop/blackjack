<?php

require_once("vendor/autoload.php");
use Pux\Mux;

$mux = new Mux;

$mux->any('/', ['Mvc\Controller\TemplateController', 'index']);

$mux->get('/game_Deal', ['Mvc\Controller\Controller', 'game_Deal']);
$mux->get('/game_Insurance', ['Mvc\Controller\Controller', 'game_Insurance']);
$mux->get('/game_Spilt', ['Mvc\Controller\Controller', 'game_Spilt']);
$mux->get('/game_Double', ['Mvc\Controller\Controller', 'game_Double']);
$mux->get('/game_Hit', ['Mvc\Controller\Controller', 'game_Hit']);
$mux->get('/game_Stand', ['Mvc\Controller\Controller', 'game_Stand']);
$mux->get('/game_Wash', ['Mvc\Controller\Controller', 'game_Wash']);
//*登入檢查
$mux->post('/loginCheck', ['Mvc\Controller\UserController', 'loginCheck']);
$mux->get('/money', ['Mvc\Controller\UserController', 'money']);
$mux->get('/logout', ['Mvc\Controller\UserController', 'logout']);

$mux->POST('/game_Sum', ['Mvc\Controller\Controller', 'game_Sum']);
return $mux;
