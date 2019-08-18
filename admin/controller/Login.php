<?php
namespace app\admin\controller;
use think\Request; 
use think\Db;
use app\admin\model\User; 
use think\View; 
use think\Controller;

class Login extends Controller
{
//-------------------------------------登录退出 -------------------------------------//
    //登录模块
    public function index()
    {
        if(request()->isPost())
        {
            // echo input('username'); 
            // echo input('password');
            $data = array(
                'username' => input('username'), 
                'password' => input('password'), 
            ); 
            $user = new User();
            $result = $user->login($data);
            if($result == 1){
                return $this->error("用户不存在"); 
            }   
            if($result == 2){
                // 设置session 
                // session('name', input('username')); 
                // 保存到日志中   
                return $this->success("登录成功", "admin/index"); 
            }
            if($result == 3){
                return $this->error("密码错误"); 
            } 
        }
        return $this->fetch();
    }

    //教师激活  
    public function active(){
        //教师输入职工号
        if(request()->post())
        {
            $tc_id = input("tc_id"); 
            $psd = input("passwd"); 
            //判断是否存在 
            $res = Db::name('teacher')->where('tc_id',$tc_id)->find();
            if($res == null)
            {
                return $this->error("没有该教师信息", "admin/login/index"); 
            }
            else
            {
                //
                $being = Db::name("auth_admin")->where("name", $tc_id)->find();   
                if($being == null)
                {
                    // $res2 = Db::name("auth_admin")->insert(array(
                    //     'name' => $tc_id,
                    //     "password" => $psd,
                    // ));
                    $user = new User();
                    $result = $user->adduser(array(
                        'username' => $tc_id,
                        'password' => $psd,
                        'describe' => "普通教师",
                    ));

                    if($result == 0)
                    {
                        $this->error("用户添加失败。。");
                    }
                    //添加到用户组  
                    $uid = Db::query("select id from auth_admin order by id desc limit 0,1");  
                    // print_r($uid);           
                    $value1 = array(
                        'uid' => $uid[0]['id'], 
                        'group_id' => 1, 
                    ); 
                    $res2 = Db::name("auth_group_access")->insert($value1); 


                    if($res2){
                        return $this->success("教师激活成功", "admin/login/index");
                    }else{
                        return $this->error("激活失败", "admin/login/index");
                    }
                }
            }
        }
        // return $this->fetch();
    }


    //退出系统 
    public function quit(){
        session('name', null);
        return  $this->success('退出成功', 'login/index');
    }

    public function test()
    {
        $view = new View(); 
        // 显示一个
        // $view->name = "haoren";
        // return $view->fetch();
        // 显示一个数组 
        $data = array(
            'name' => 'haoren', 
            'sex' => 'man', 
        ); 
        // $this->assign([
        //     'age'  => 'ThinkPHP',
        //     'email' => 'thinkphp@qq.com'
        // ]);
        // $data['name'] = 'haoren';
        // $data['sex'] = 'man';  
        $view->assign('data', $data); 
        return $view->fetch();
    }



}