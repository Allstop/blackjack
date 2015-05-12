<?php

namespace Mvc\Model;

class Model
{

    public $suits = array("♣", "♥", "♠", "♦");

    public $values = array("A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K");

    public function createDeck()
    {
        $deck = array();
        for ($i = 0; $i < 13; $i++)
        {
            for($j = 0; $j < 4; $j++)
            {
                array_push($deck,$this->values[$i].$this->suits[$j]);
            }
        }
        //亂數排序
        shuffle($deck);
        return $deck;
    }
    public function game_Sum($data){
        $output = "";
        $sum = "";
        foreach ($data as $key=>$value) {
            if ($key!='deck') {
                for($i=1;$i<count($data[$key])+1;$i++){
                    $sum[$i] = substr($data[$key][$i],0,-3);
                    if (preg_match('/[JQK]/', $sum[$i])) {
                        $sum[$i] = 10;
                    } elseif (preg_match('/[A]/', $sum[$i])) {
                        $sum[$i] = 11;
                    }
                    $output[$key] .= $data[$key][$i];
                    $output[$key] .= " ";
                }
                $sumValue[$key] = array_sum($sum);
                if (in_array(11,$sum) && $sumValue[$key]>21) {
                    for($i=1;$i<count($data[$key])+1;$i++){
                        if (preg_match('/11/', $sum[$i])) {
                            while ($sumValue[$key]>21) {
                                $sum[$i] = 1;
                                $sumValue[$key] = array_sum($sum);
                                break;
                            }
                        }
                    }
                }
            }
        }

        return array(output=>$output, sumValue=>$sumValue);
    }
    public function game_Hit($_data, $_deck)
    {
        $j = count($_data)+1;
        $_data[$j] = array_pop($_deck);
        return array(data=>$_data, deck=>$_deck);
    }

    public function game_Fold($data, $sum)
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
