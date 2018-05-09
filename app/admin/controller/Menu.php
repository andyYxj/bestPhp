<?php
/**
 *
 * Created by  yuxianjun001@icloud.com.
 * User: wuchen
 * Date: 2018/1/19
 * Time: 16:49
 */

namespace App\admin\controller;
use App\admin\model\Menu as m;
use Illuminate\Support\Facades\DB;
use  Common\Controller\Controller; //基类控制器

class Menu extends Controller
{

    public function index(){
        $data=DB::table('bg_cate')->get();
        var_dump($data);die;
        $tree = $this->getTree($data, 0);
        echo  $this->procHtml($tree);
    }

    public function getTree($data, $pId)
    {
        $tree = '';
        foreach($data as $k => $v)
        {
            if($v['cate_ParentId'] == $pId)
            {
                //父亲找到儿子
                $v['cate_ParentId'] = getTree($data, $v['cate_Id']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

   public function procHtml($tree)
    {
        $html = '';
        foreach($tree as $t)
        {
            if($t['cate_ParentId'] == '')
            {
                $html .= "<li>{$t['cate_Name']}</li>";
            }
            else
            {
                $html .= "<li>".$t['cate_Name'];
                $html .= procHtml($t['cate_ParentId']);
                $html = $html."</li>";
            }
        }
        return $html ? '<ul>'.$html.'</ul>' : $html ;
    }

}