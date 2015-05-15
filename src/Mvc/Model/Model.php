<?php

namespace Mvc\Model;

class Model
{
    private static $suits = array("♣", "♥", "♠", "♦");

    private static $values = array("A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K");
    public static $user = null;

    public static function init()
    {
        if (!static::$user) {
            static::$user = new self();
        }
        return static::$user;
    }

    public function game_Deal()
    {
        $deck = array();
        for ($i = 0; $i < 13; $i++)
        {
            for($j = 0; $j < 4; $j++)
            {
                array_push($deck, self::$values[$i].self::$suits[$j]);
            }
        }
        //亂數排序
        shuffle($deck);
        $first[1] = array_pop($deck);
        $a[2] = array_pop($deck);
        $b[1] = array_pop($deck);
        $b[2] = array_pop($deck);
        return array(f=>$first, a=>$a, b=>$b, deck=>$deck);
    }

    public function game_Sum($data){
        $sum = "";
        foreach ($data as $key=>$value) {
            if (preg_match('/[0-9]/',$key)) {
                $sum[$key]=substr($data[$key],0,-3);
            }
            if (preg_match('/[JQK]/', $sum[$key])) {
                $sum[$key] = 10;
            } elseif (preg_match('/[A]/', $sum[$key])) {
                $sum[$key] = 11;
            }
            $sumValue = array_sum($sum);
        }
        foreach ($sum as $key=>$value) {
            while (preg_match('/11/', $sum[$key]) && $sumValue>21) {
                $sum[$key] = 1;
                $sumValue = array_sum($sum);
            }
        }
        return array(num=>$sum, sumValue=>$sumValue);
    }

    public function game_Spilt($data){
        $b1[1] =$data[1] ;
        $b1['num'][1] =$data['num'][1] ;
        $b2[1] =$data[2] ;
        $b2['num'][1] =$data['num'][2] ;
        return array(b=>$b1, b1=>$b2);
    }

    public function game_Hit($_data, $_deck)
    {
        $j=count($_data)-1;
        $_data[$j] = array_pop($_deck);
        return array(num=>$j, data=>$_data[$j], deck=>$_deck);
    }

    public function game_Stand($data)
    {
        $data['a'][1]=$data['f'][1];
        $data['a']['num']=$this->game_Sum($data['a'])['num'];
        $data['a']['sum']=$this->game_Sum($data['a'])['sumValue'];
        if ($data['b']['sum']>21 ) {
            $ans="You lose!";
            $multiple=0;
        } else {
            while ($data['a']['sum']<17) {
                $aa=$this->game_Hit($data['a'], $data['deck']);
                $data['a'][$aa['num']]=$aa['data'];
                $data['a']['num']=$this->game_Sum($data['a'])['num'];
                $data['a']['sum']=$this->game_Sum($data['a'])['sumValue'];
            }
            //過五關*2
            if (count($data['b'])-2>4) {
                if (count($data['a'])-2>4 && $data['a']['sum']<22) {
                    $ans="START!!! You lose!";
                    $multiple=0;
                } else {
                    $ans="START!!! You win!";
                    $multiple=2;
                }
            //black*1.5
            } elseif (count($data['b'])-2 == 2 && $data['b']['sum'] == 21) {
                if (count($data['a'])-2 == 2 && $data['a']['sum'] == 21) {
                    $ans="GM also Black Jack!!! You lose!";
                    $multiple=0;
                } else {
                    $ans="Black Jack!!! You win!";
                    $multiple=1.5;
                }
            } elseif ($data['a']['sum']>21) {
                $ans="You win!";
                $multiple=1;
            } elseif ($data['b']['sum']>$data['a']['sum']) {
                $ans="You win!";
                $multiple=1;
            } else {
                $ans="You lose!";
                $multiple=0;
            }
        }

        $result=array(a=>$data['a'],result=>$ans, multiple=>$multiple);
        return $result;
    }
}
