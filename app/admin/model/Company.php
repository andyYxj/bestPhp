<?php

/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2017/12/11
 * Time: 13:53
 */

namespace App\admin\model;
use Illuminate\Database\Eloquent\Model;
class Company  extends Model
{
    public $timestamps=false;
    public $table='company';
    public $primaryKey='id';

}