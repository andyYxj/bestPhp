<?php
/**
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/19
 * Time: 16:52
 */

namespace App\admin\model;
use Illuminate\Database\Eloquent\Model;

class Menu  extends Model
{
    public $timestamps=false;
    public $table='bg_cate';
    public $primaryKey='cate_Id';

}