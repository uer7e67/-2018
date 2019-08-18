<?php  
namespace app\admin\controller; 
use think\Controller; 
use think\Db; 
use think\Request; 

class BaseController extends Controller 
{
    protected function _initialize()
    {
        // parent::initialize();
        if(!session('name')){
            return $this->error("你还没有登录..", "Login/index"); 
        }

        $request = Request::instance(); 
        $auth = new Auth();
        $name = $request->module() . '/' . $request->controller() . '/' . $request->action(); 
        // echo $name; 
        $uid  = session("id"); 
        // $type = MODULE_NAME;
        if(!$auth->check($name, $uid)){
            // return $this->error("没有权限","javascript:top.location.href='admin/index/index'");
            return $this->error("没有权限..", "admin/admin/kong"); 

        }

    }
}