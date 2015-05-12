<?php

require_once("vendor/autoload.php");
use Pux\Mux;

$mux = new Mux;

$mux->any('/', ['Mvc\Controller\TemplateController', 'index']);

$mux->get('/createDeck', ['Mvc\Controller\Controller', 'createDeck']);
$mux->get('/game_Reset', ['Mvc\Controller\Controller', 'game_Reset']);
$mux->post('/game_Sum', ['Mvc\Controller\Controller', 'game_Sum']);
$mux->get('/game_Hit', ['Mvc\Controller\Controller', 'game_Hit']);
$mux->get('/game_Fold', ['Mvc\Controller\Controller', 'game_Fold']);

return $mux;
