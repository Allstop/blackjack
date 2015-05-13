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
        return array(b1=>$b1, b2=>$b2);
    }
    public function game_Hit($_data, $_deck)
    {
        $j=count($_data)-1;
        $_data[$j] = array_pop($_deck);
        return array(num=>$j, data=>$_data[$j], deck=>$_deck);
    }

    public function game_Stand($data, $sum)
    {
        $a=$this->game_Sum($data)['output']['a'];
        $sum['a']=$this->game_Sum($data)['sumValue']['a'];

        if ($sum['b']>21) {
            $ans="BOOM!!! You lose!";
        } else {
            while ($sum['a']<17) {
                $aa=$this->game_Hit($data['a'], $data['deck']);
                $data['a']=$aa['data'];
                $a=$this->game_Sum($data)['output']['a'];
                $sum['a']=$this->game_Sum($data)['sumValue']['a'];
            }
            if (count($data['a'])>4) {
                $ans="START!!! You lose!";
            } elseif (count($data['b'])>4) {
                $ans="START!!! You win!";
            } elseif ($sum['a']>21) {
                $ans="You win!";
            } elseif ($sum['b']>$sum['a']) {
                $ans="You win!";
            } else {
                $ans="You lose!";
            }
        }
        $result=array_merge(array(output=>$a, sumValue=>$sum['a']), array(ans=>$ans));
        return $result;
    }
}
