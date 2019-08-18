<?php
namespace app\admin\model;
use think\Model;
use think\Db; 

class User extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function login($data){
        // $result = User::getByName($data['username']); 
        $sql = "select * from auth_admin where name = ? ";
        $result = Db::query($sql, [$data['username']]);
        if(count($result) == 0){
            return 1;  //用户名不存在
        }
        $result = $result[0]; 
        if($result)
        {
            if(md5($data['password']) == $result['password'])
            {
                $uid = $result['id']; 
                session('name', $data['username']); 
                session('id', $uid);
                $time = date('Y-m-d H:i:s');
                //存入数据库中 
                Db::name("access_log")->insert(array(
                    'time' => $time, 
                    'uid'  => $uid,
                    'username'  => $data['username'], 
                    'ip' => $this->getClientIp(),
                )); 
                // session('name', input('username')); 
                return 2;   //登录成功 
            }
            else{
                return 3;   //密码错误    
            }
        }    
    }

    public function adduser($data)
    {
        $sql = "insert into auth_admin(name, password) values(?, ?)"; 
        $result = Db::execute( $sql, [$data['username'], md5($data['password'])]);
        if(count($result) == 1)
        {
            return 1;   //保存成功 
        } 
        else{
            return 0 ;   //保存失败
        }
    }


    public function deluser($id)
    {        
        //第一步 从admin表中删除  
        $res1 = Db::name("auth_admin")->where("id", $id)->delete();
        //第二步  从权限关联表中删除   
        $res2 = Db::name("auth_group_access")->where("uid", $id)->delete();

        if($res1 == 1 && $res2 == 1){
            return 1; 
        }else{
            return 2; 
        }

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
    // public function 
}