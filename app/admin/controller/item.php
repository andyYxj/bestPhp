<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Curl\Curl;
use Grafika\Grafika; // 图像处理
use \Grafika\Color;
use common\controllers\PCAccountController;
include dirname(dirname(dirname(__FILE__))) .DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."phpqrcode".DIRECTORY_SEPARATOR."phpqrcode.php";
/**
 * 基础商品管理相关
 * Created by PhpStorm.
 * User: wuchen
 * Date: 2017/6/13 0013
 * Time: 9:59
 */
class Item extends PCAccountController
{
    public function __construct()
    {
        parent::__construct();
        $this->qiniuConfig = config_item('qiniu');
        $this->load->service('short_url_service');
        $this->load->helper('file');
        $this->load->helper('image');
        $this->load->service('upload_service');
    }
    /**
     * 新增/修改商品
     */
    public function addItem()
    {
        try {
            $shop_id = $this->input->get_post('shop_id') ? intval(trim($this->input->get_post('shop_id'))) : '';
            $item_url_outer = $this->input->get_post('item_url_outer') ? trim($this->input->get_post('item_url_outer')) : '';
            $item_sale_price = !empty($this->input->get_post('item_sale_price', true))?trim($this->input->get_post('item_sale_price', true)):'';
            $item_title =!empty($this->input->get_post('item_title', true))?trim($this->input->get_post('item_title', true)):'';
            $item_type = !empty($this->input->get_post('item_type', true))?trim($this->input->get_post('item_type', true)):1;//商品类型，线上1，线下2
            $item_title = urldecode(mb_convert_encoding(urlencode($item_title), 'UTF-8', 'GBK'));
            $item_main_img = $img_full_path = trim($this->input->get_post('full_path', true));//主图，七牛云路径
            $item_id = intval(trim($this->input->get_post('item_id', true)));//item_id商品id
            $plat_from_logo =!empty($this->input->get_post('plat_from_logo', true))?trim($this->input->get_post('plat_from_logo', true)):'';
            $item_from =!empty($this->input->get_post('item_from', true))?intval($this->input->get_post('item_from', true)):0;
            $ext = getImgExt($img_full_path);
            $options = array('jpg', 'jpeg', 'png');
            if (!in_array($ext, $options)) {
                $this->response(1000, '图片文件格式不被支持!');
            }

            if (mb_strlen($item_title) > 60) {
                $this->response(1000, '商品标题不能大于60字符！');
            }
            if($item_type==1){
                //线上商品
                $shop_name = $this->input->get_post('shop_name', true) ? trim($this->input->get_post('shop_name', true)) : $this->response(1000, 'shop_name 必填！');
                $qrcode_type = $this->input->get_post('qrcode_type') ? trim($this->input->get_post('qrcode_type')) : $this->response(1000, 'qrcode_type 必填！');
                if ($qrcode_type == 'withImg' || $qrcode_type == 'noImg') {
                    $qrcode_type = $qrcode_type;
                } else {
                    $this->response(1000, 'qrcode_type 参数错误！');
                }

                //判断是否是淘宝，天猫连接
                $isTaoTmall = strpos($item_url_outer, 'taobao') || strpos($item_url_outer, 'tmall');
                $qr_code = '';
                $item_url_inner = '';
                if ($isTaoTmall) {
                    $data = $this->parseUrl($item_url_outer);
                    if ($data == -1) {
                        throw new Exception("挖到短连转换错误！");
                    }
                    $item_url_inner = $this->short_url_service->toShortUrl($data['short_url']);//转为短连接
                    //淘宝，天猫商品，不必合成二维码，直接合成图片
                    $filePath = $this->createMultiImg($item_sale_price, $data['qr_code'], $img_full_path, $item_title, $type = 'wadao');
                    $qrcode_multi_img_url = $this->img_up_qiniu($filePath);//带主图的二维码，上传，返回地址
                    $qr_code = array($qrcode_multi_img_url, $data['qr_code']);
                } else {
                    //非淘宝天猫商品，要生成二维码，进行拼图
                    $item_url_inner = $this->short_url_service->toShortUrl($item_url_outer);//需要前端调用接口，转为短连接
                    $qrcode_path = $this->createQrcode($item_url_inner);
                    $qrcode_img_url = $this->img_up_qiniu($qrcode_path);//二维码上传,返回地址
                    //合成图片上传
                    $filePath = $this->createMultiImg($item_sale_price, $qrcode_path, $img_full_path, $item_title, $type = 'local');//合成图片，返回本地图片地址
                    $qrcode_multi_img_url = $this->img_up_qiniu($filePath);//带主图的二维码，上传
                    $qr_code = array($qrcode_multi_img_url, $qrcode_img_url);
                    @unlink($qrcode_path); //删除对应文件
                }

                //构造商品数据
                $data = array(
                    'app_id'=>$this->wechatApp->getAppId(),
                    'shop_id' => $shop_id,
                    'shop_name' => $shop_name,
                    'visit_id' => $this->visit_id,
                    'platform_logo' => $plat_from_logo,
                    'item_url_outer' => $item_url_outer,
                    'item_url_inner' => $item_url_inner,
                    'item_sale_price' => $item_sale_price,
                    'item_main_img' => $item_main_img,
                    'qr_code_img_url' => $qr_code['0'],
                    'qr_code_url' => $qr_code['1'],
                    'item_title' => $item_title,
                    'qrcode_type' => $qrcode_type,
                    'update_time' => date('Y-m-d H:i:s'),
                    'item_type'=>$item_type,
                    'item_from'=>$item_from,
                );
            }else{
                //线下商品
                $data=[
                    'app_id'=>$this->wechatApp->getAppId(),
                    'visit_id' => $this->visit_id,
                    'item_sale_price' => $item_sale_price,
                    'item_main_img' => $item_main_img,
                    'item_title' => $item_title,
                    'update_time' => date('Y-m-d H:i:s'),
                    'item_type'=>$item_type,
                ];
            }


            // @unlink($filePath); //删除对应文件,公共处理部分(正式环境打开)
            //@unlink($img_full_path);
            //入库
            $this->load->model('crm_item', 'crm_itemModel', 'default', $this->visit_id, false);//false后才可以主库，分库切换

            if (!empty($item_id) && is_numeric($item_id)) {
                //编辑，更新
                //编辑状态，入库之前进行删除七牛云操作
                $this->deleteQiniu($item_id);
                $res = $this->crm_itemModel->updateDate($data, $item_id,$this->visit_id);
                if ($res) {
                    $this->response(0, '商品修改成功！');
                } else {
                    $this->response(1000, '商品修改失败！');
                }
            } else {
                //新增
                $res = $this->crm_itemModel->add($data);
                if ($res) {
                    $this->response(0, '新增商品成功！');
                } else {
                    $this->response(1000, '新增商品失败！');
                }
            }
        }catch(Exception $e){
            print $e->getMessage();
            exit();
        }
    }


    /**
     * $url 要被解析的淘宝，天猫地址
     * 解析商品信息
     */
    public function parseUrl($item_url_outer)
    {
        $wadaoTransferAPi = "http://dg.wadao.com/conversion/mix2wxUrl";//挖到解析接口
        //检测是否是淘宝，天猫平台网址
        $isTaoTmall = strpos($item_url_outer, 'taobao') || strpos($item_url_outer, 'tmall');
        if ($isTaoTmall) {
            $curl = new Curl();
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->setOpt(CURLOPT_HEADER, false);
            $curl->post($wadaoTransferAPi, array(
                'url' => urlencode($item_url_outer),
                'api' => 'true',
            ));
            $curl->close();
            if ($curl->error) {
                return -1;
            } else {
                $data = \GuzzleHttp\json_decode($curl->response, true);
                if ($data['success'] = true) {
                     return $data['data'];
                }
                if($data['success'] = false){
                    return -1;
                }
            }

        } else {
            return -1;
        }
    }

    /**
     * 生成二维码
     */
    public function  createQrcode($url)
    {
        //公共部分
       try {
           makeDir("./uploads/qrCode");
           $errorCorrectionLevel = 'L';  // 错误校正
           $matrixPointSize = 10;  // 边界空白位置
           $tmpFile = md5(mt_rand());
           $qrcode_path = './uploads/qrCode/' . $tmpFile . '.png';
           QRcode::png($url, $qrcode_path, $errorCorrectionLevel, $matrixPointSize, 2); //生成二维码
           chmod($qrcode_path, 0777);
           //单纯二维码上传七牛云
           $qrcode_2 = $this->img_up_qiniu($qrcode_path);//不带主图的二维码，也要上传
       }catch(Exception $e){
           print $e->getMessage();
           exit();
       }
        return $qrcode_path;
    }

    /**
     * 多张图片合成一张图片
     */
    public function createMultiImg($item_sale_price, $qrcode_path, $img_full_path, $item_title, $type)
    {
        try {
           // $tmpPath='/usr/local/nginx/html/scrm/public/uploads/qrCode';
            $tmpPath=APPPATH.'public/uploads/qrCode';
            makeDir($tmpPath);
            $blank = $tmpPath.'/blank.png';
            $editor = Grafika::createEditor();
            if (!is_readable($blank)) {
                //如果文件不存在或者不可读
                //创建空白画布用gd原生库
                $imageback = imagecreate(800, 1144); //创建空白画布
                imagecolorallocate($imageback, 255, 255, 255);
                imagepng($imageback, $tmpPath.'/blank.png');
                @chmod($tmpPath."/blank.png", 0777);
            }


             $editor->open($imageBack, $tmpPath.'/blank.png'); // 打开$imageback并且存放到$imageBack
             $imageMain=@imagecreatefromExt($img_full_path);//辅助函数
             $name='main';
             $path=$tmpPath;
             $fileType=getImgExt($img_full_path);
             resizeImage($imageMain,800,1144,$name,$fileType,$path); //等比压缩商品主图
             saveImgByExt($imageMain,$path,$name,$fileType);
             $editor->open($imageMain, $path.$name.'.'.$fileType);//打开处理过的背景主图(商品图片)
            if ($type == 'wadao') {
                $im = @imagecreatefrompng($qrcode_path);
                imagepng($im, $tmpPath.'/qrcode_wadao.png');
                $qrcode_path = $tmpPath.'/qrcode_wadao.png';
                @chmod($tmpPath."/qrcode_wadao.png", 0777);
            }
            $editor->open($imageQrcode, $qrcode_path);//打开二维码
            $editor->resizeExact($imageMain, 800, 800);//调整下背景主图大小
            $editor->resizeExact($imageQrcode, 228, 228);//调整下二维码大小
            $editor->blend($imageBack, $imageMain, 'normal', 1, 'top-center');
            $editor->blend($imageBack, $imageQrcode, 'normal', 1, 'bottom-right',-30,-50);//距离右边
            $front = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'PingFang Regular.ttf';
            $strLen=mb_strlen($item_title,'utf8');//字符长度
            //字体大小30-29
            if($strLen<=15){
                $editor->text($imageBack, $item_title, 29, 10, 916, new Color('#030303'), $front, 0);
            }
            if($strLen>15 && $strLen<=30){
                //处理成2行文字
                $item_title_1= mb_substr($item_title,0,15,'utf-8');//第一行
                $item_title_2=mb_substr($item_title,15,15,'utf-8');//第二行
                $editor->text($imageBack, $item_title_1, 29, 20, 880, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_2, 29, 20, 922, new Color('#030303'), $front, 0);
            }
            if($strLen>30 && $strLen<=45){
                //处理成3行文字
                $item_title_1= mb_substr($item_title,0,15,'utf-8');//第一行
                $item_title_2=mb_substr($item_title,15,15,'utf-8');//第二行
                $item_title_3=mb_substr($item_title,30,15,'utf-8');//
                $editor->text($imageBack, $item_title_1, 29, 20, 880, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_2, 29, 20, 922, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_3, 29, 20, 964, new Color('#030303'), $front, 0);
            }
            if($strLen>45 && $strLen<=60){
                //处理成4行文字
                $item_title_1= mb_substr($item_title,0,15,'utf-8');//第一行
                $item_title_2=mb_substr($item_title,15,15,'utf-8');//第二行
                $item_title_3=mb_substr($item_title,30,15,'utf-8');//第三行
                $item_title_4=mb_substr($item_title,45,15,'utf-8');//第四行
                $editor->text($imageBack, $item_title_1, 29, 20, 880, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_2, 29, 20, 922, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_3, 29, 20, 964, new Color('#030303'), $front, 0);
                $editor->text($imageBack, $item_title_4, 29 , 20, 1006, new Color('#030303'), $front, 0);
            }
            $editor->text($imageBack, '现价 ', 28, 20, 1050, new Color('#A4A4A4'), $front, 0);
            $editor->text($imageBack, '￥', 28, 90, 1050, new Color('#FF5603'), $front, 0);
            $editor->text($imageBack, $item_sale_price, 40, 130, 1044, new Color('#FF5603'), $front, 0);
            $editor->text($imageBack, '长按图片识别', 19, 585, 1090, new Color('#FF5603'), $front, 0);
            $editor->save($imageBack, $tmpPath.'/main_end.png');//保存调整过的主图
            @chmod($tmpPath."/main_end.png", 0755);
        } catch (Exception $e) {
            print $e->getMessage();
        }
        return $tmpPath.'/main_end.png';
    }

    /**
     * 图片上传七牛云
     * @param $qrcode_path
     * @return string
     */
    public function img_up_qiniu($qrcode_path)
    {
        //二维码上传七牛云
        if (config_item('upload_type') == 'qiniu') {
            try {
                $this->load->service('upload_service');
                $headImg = $this->upload_service->up_images($qrcode_path, date('His') . $this->company_id);
                $img_url = $this->qiniuConfig['domain'] . $headImg;//图片在七牛的url地址
            }catch (Exception $e){
                print_r($e->getMessage());
                exit;
            }
            return $img_url;
        }
    }

    /**
     * 查看一条记录
     */
    public function getItemInfo()
    {
        //$item_id = !empty($this->input->get_post('item_id'))?intval($this->input->get_post('item_id'), true):$this->response(1000,'item_id必填!');//item_id商品id
        $item_id=!empty($this->input->get_post('item_id'))?intval($this->input->get_post('item_id')):$this->response(1000,'item_id必填!');
        $this->load->model('crm_item', 'crm_itemModel', 'default', $this->visit_id, false);//false后才可以主库，分库切换
        $options['where'] = array('item_id' => $item_id);
        $res=$this->crm_itemModel->info($options);
        if(!empty($res)){
            $this->response(0,$res);
        }else{
            $this->response(1000,'查询失败!');
        }
    }
    /**
     * 获取商品列表
     */
    public function getItems()
    {
        $item_type = !empty($this->input->get_post('item_type', true)) ? intval($this->input->get_post('item_type', true)) : 0;
        $shop_id = !empty($this->input->get_post('shop_id', true)) ? intval($this->input->get_post('shop_id', true)) : '';
        $item_title=!empty($this->input->get_post('item_title', true)) ? $this->input->get_post('item_title', true):'';
        $limit = !empty($this->input->get_post('limit', true)) ? intval($this->input->get_post('limit', true)) : 10;//每页显示数量
        $page = !empty($this->input->get_post('page', true)) ? intval($this->input->get_post('page', true)) : 1; //当前第几页
        //搜索条件
        //都为空
        if(empty($shop_id) && empty($item_title)){
            $options = array('where' => array(
                'app_id'=>$this->wechatApp->getAppId(),
                'visit_id' => $this->visit_id,
                'is_delete'=>0,
                'item_type'=>$item_type,
            ));
        }
        //都不为空
        if( !empty($shop_id) && !empty($item_title) ){
            $options = array('where' => array(
                'shop_id' => $shop_id,
                'visit_id' => $this->visit_id,
                'app_id'=>$this->wechatApp->getAppId(),
                'is_delete'=>0,
                'item_type'=>$item_type,
            ),
                'like'=>array('item_title'=>$item_title,),
            );
        }
        //商品标题为空
        if(!empty($shop_id) && empty($item_title)){
            $options = array('where' => array(
                'shop_id' => $shop_id,
                'visit_id' => $this->visit_id,
                'app_id'=>$this->wechatApp->getAppId(),
                'is_delete'=>0,
                'item_type'=>$item_type,
            ),
                'like'=>array('item_title'=>$item_title,),
            );
        }
        //店铺id为空
        if(empty($shop_id) && !empty($item_title)){
            $options = array(
                'where' => array(
                'visit_id' => $this->visit_id,
                    'app_id'=>$this->wechatApp->getAppId(),
                'is_delete'=>0,
                    'item_type'=>$item_type,
            ),
            'like'=>array('item_title'=>$item_title,),
                );
        }

        $orderby = array(
            'item_id' => 'desc',
        );
        if($item_type==0){
            unset($options['where']['item_type']);
        }
        $this->load->model('crm_item', 'crm_itemModel', 'default', $this->visit_id, false);//false后才可以主库，分库切换
        $data = $this->crm_itemModel->lists($limit, $page, $options, $orderby);
        if (!empty($data)) {
            $this->response(0, $data);
        } else {
            $this->response(1000, '获取数据失败！');
        }

    }

    /**
     * 删除某个商品
     */
    public function delItem(){
        $item_id = !empty($this->input->get_post('item_id', true)) ? intval($this->input->get_post('item_id', true)) : '';
        $data=array(
            'is_delete'=>1,
        );
        if(empty($item_id)){
            $this->response(1000,'item_id不能为空！');
        }
        $this->load->model('crm_item', 'crm_itemModel', 'default', $this->visit_id, false);//false后才可以主库，分库切换
        $data = $this->crm_itemModel->updateDate($data,$item_id,$this->visit_id);
        if($data){
            $this->response(0,'商品删除成功！');
        }else{
            $this->response(1000,'商品删除失败！');
        }
    }

    /**
     * 编辑的时候才去检测
     * $key 上传的时候返回的图片地址
     * 检测是否要删除七牛云文件
     */
    public function deleteQiniu($item_id){
        $options=array(
            'item_id'=>$item_id,
        );
        $this->load->model('crm_item', 'crm_itemModel', 'default', $this->visit_id, false);//false后才可以主库，分库切换
        $res=$this->crm_itemModel->infos($options);
        $qr_code_img_url=$res['qr_code_img_url'];//合成的带主图的二维码图
        $item_main_img=$res['item_main_img'];
        $key_qrImg_url=ltrim($qr_code_img_url,$this->qiniuConfig['domain']);//合成图片
        $key_main_url=ltrim($item_main_img,$this->qiniuConfig['domain']);//商品主图
            if (config_item('upload_type') == 'qiniu') {
                $this->load->service('upload_service');
                $this->upload_service->delete($key_qrImg_url);
            }
        return ;
    }
    //文件上传单独处理
    public function file_upload()
    {
        makeDir('./uploads/qrCode');
        $config['upload_path'] = './uploads/qrCode/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '10240';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error =  $this->upload->display_errors();
            $this->response(1000, $error);//上传失败
        } else {
            $data = array('upload_data' => $this->upload->data());
        }
        $file = $data['upload_data']['full_path'];//文件名字

        if(config_item('upload_type') == 'qiniu')
        {
            $this->load->service('upload_service');
            $headImg = $this->upload_service->up_images($file, date('His').$this->company_id);
            $headImg = $this->qiniuConfig['domain'].$headImg;
            $data=array('images_url'=>$headImg);
            $this->response(0,$data);
        }else{
            $this->response(1000,'七牛云未配置！');
        }

    }

    // 批量更新crm_item的商品来源字段item_from
    public function update()
    {
        for($i=0;$i<10;$i++){
            for($j=0;$j<10;$j++){
                $tableName = 'xdianshang_crm_'.$i.'.crm_item_'.$j;
                $sql = 'select * from '.$tableName.' where item_from = 0';
                $list = $this->db->query($sql)->result_array();//只返回一行
                if(count($list) > 0)echo $i.'-'.$j.'-'.count($list);
                if(empty($list)) continue;
                $num = 0;
                foreach($list as $val){
                    if($val['shop_id'] == 0 || $val['visit_id'] == 0) continue;
                    $this->load->model('shops', 'shopModel' , 'default');
                    $shop = $this->shopModel->info(['where'=>[
                        'shop_id' => $val['shop_id'],
                        'visit_id' => $val['visit_id']
                    ]]);
                    if(empty($shop)) continue;
                    $query = 'UPDATE '.$tableName.' SET item_from='.$shop['plat_from'].' where item_id='.$val['item_id'];
                    $id = $this->db->query($query);
                    if($id > 0) $num++;
                }
                echo "更新数量：".$num."<br>";
            }
        }

    }

}