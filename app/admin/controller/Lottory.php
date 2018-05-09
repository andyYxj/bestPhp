<?php
/**
 * 简易抽奖
 * Created by
 * yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/2/2
 * Time: 10:07
 */

namespace App\admin\controller;


class Lottory
{
    private    $names=[
    '断浪',
    '无尘',
    '饺子',
    '雪莉',
    '娜美'
    ];


    public function  start(){
        //出来一个
        $name=array_rand($this->names,1);
        //剔除已经中奖的人
        return $name;
    }

    //显示剩余的人数
    public function showRemainingPerson(){


    }

}