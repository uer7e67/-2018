<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Admin extends BaseController
{
    //index
    public function index()
    {
        
        $username = session('name');
        // print_r($username); 
        $this->assign('name', $username); 
        $res = Db::name("auth_admin")->where("name", $username)->select(); 
        $this->assign("describe", $res[0]['describe']);

        $info = Db::name("web_info")->select();
        $this->assign("info", $info[0]);

        return $this->fetch(); 
    }
    //桌面 
    public function window()
    {
        // echo __DIR__;
        $uid = session("id"); 
        $res = Db::query("select time from access_log where uid=? order by time DESC LIMIT 1,1", [$uid]);
        // print_r($res);
        if(count($res) == 1)
        {
            $list_time = $res[0]['time']; 
            
        }
        else
        {
            $list_time = "第一次登录，欢迎使用";
        }
        $this->assign('list_time', $list_time);
        $id = $this->getClientIp();

        $info = Db::name("web_info")->select();
        $this->assign("info", $info[0]);
        
        return $this->fetch(); 
    }

    private function getClientIp($type=0){
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('127.0.0.1', 0);
        return $ip[$type];
    }
//-------------------------------------更新代码-------------------------------------//  
    //数据库维护 
    public function updatacode()
    {
        return $this->fetch();
    }


    //下载服务  
    public function download(){
        
        return $this->fetch();
    }


    public function kong(){
        return $this->fetch();
    }


}



// $file = request()->file('excel');
// if(empty($file)){
//     return $this->fetch();
// }
// $info = $file->move(__PUBLIC__); 
// echo "info1" + $info->getExtension();
// if($info){
//     $this->success("文件上传成功...");
// }


// if($file){
//     $info = $file->move(__PUBLIC__);
// }  
// return $this->fetch();

// else if(!empty($_FILES['excle_list']['name']))
// {
//     echo "hello";
//     //$file_type = $_FILES["excle_list"]['type']; 
//     $file_tmp = $_FILES['excle_list']['tmp_name'];
//     $file_types = explode('.', $file_tmp);
//     $file_type = $file_types[count($file_types)-1];  //获取到文件的类型 
//     $savapath = __UPEXCLE__; 
//     echo  $file_type;
//     if($file_type != 'xls')
//     {
//         return $this->error("不是excel文件，请重新上传");
//     }   
//     // if(!copy($))s
//     return $this->error("成功");
// }
// else
// {
//     return $this->error();
// }

// $file = request()->file('excel');
// if(empty($file)){
//     return $this->fetch();
// }
// $info = $file->move(__PUBLIC__); 
// echo "info1" + $info->getExtension();
// if($info){
//     $this->success("文件上传成功...");
// }


    // public function upload()
    // {
    //     $upload = new Upload();
    //     $upload->maxSize = 3145728; 
    //     $upload->exts = array('jpg', 'gif');
    //     $upload->savePath = "./";
    //     $info = $upload->upload(); 
    //     if(!info){
    //         $this->error($upload->getError());
    //     } 
    //     else{
    //         $this->success("上传成功 ...");
    //     }
    // }

        // print_r(ROOT_PATH . 'public' . DS . 'upfile'); 
        // $wenben = $_POST("wenben");
        // if($wenben)
        // {
        //     echo $wenben;
        // }