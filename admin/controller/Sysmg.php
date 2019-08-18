<?php  
namespace app\admin\controller; 
use think\Db; 
use think\Controller; 
use app\admin\model\User;

class Sysmg extends BaseController
{
    // 管理员列表   
    public function admin_lst()
    {
        // $userlst = DB::query("select * from auth_admin");  
        $adminlst = Db::name("auth_admin")->paginate(5);
        if($adminlst != null)
        {
            $this->assign('adminlst', $adminlst); 
        }                                                                                                              
        return $this->fetch();
    }
    //添加管理员   
    public function add_admin()
    {
        if(request()->post())
        {
            $exist = Db::table("auth_admin")->where("name",input("admin_name"))->find(); 
            if($exist){
                $this->error("存在该用户 ... ", "admin/sysmg/admin_lst");
            }
            //添加到管理员表
            $value = array(
                'username' => input('admin_name'), 
                'password' => input('password'),
            ); 
            $user = new User();
            $result = $user->adduser($value); 
            //关联到用户组
            $uid = Db::query("select id from auth_admin order by id desc limit 0,1");  
            print_r($uid);           
            $group_id = input("group_id");
            $value1 = array(
                'uid' => $uid[0]['id'], 
                'group_id' => $group_id, 
            ); 
            Db::name("auth_group_access")->insert($value1); 
            
            if($result == 1)
            {
                return $this->success("管理员添加成功", 'admin/sysmg/admin_lst');
            }
            else{
                return $this->error("添加失败");
            }
            // Db::query('select * from think_user where id=?',[8]);
            // $result = Db::execute("insert into user(name, password) values(?, ?)", $admin_name, $password); 
        }else 
        {
            $auth_group = Db::name("auth_group")->select();
            $this->assign("auth_group", $auth_group); 
        }
        return $this->fetch(); 
    } 
    //删除 
    public function del_admin($id)
    {
        $user = new User();
        $result = $user->deluser($id); 

        if($result == 1)
        {
            $this->success("删除成功", "admin/sysmg/admin_lst");
        } 
        else 
        {
            $this->error("删除失败", "admin/sysmg/admin_lst");
        }
    }


    public function update_admin(){

        if(request()->post())
        {
            $id = input("id"); 
            // $user = new User();
            // $res = $user->adduser($value); 
            $res = Db::name("auth_admin")->where("id", $id)->update(array(
                "name" => input("name"),
                "password" => md5(input("password")),
            ));
            if($res){
                $this->success("用户更新成功 ... ");                 
            } 
            else{
                $this->error("用户更新失败 ... ");
            }
        }
        else
        {
            $id = input("id");
            $view = Db::name("auth_admin")->where("id", $id)->select();
            $this->assign("view", $view[0]);
        }
        return $this->fetch();
    }


    // 用户组列表  -----------------------------------------------------------------------------------------------------
    public function role_lst()
    {
        //1 超级管理员  2 普通管理员 3 老师 
        //id：主键， title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
        $rolelst = Db::name("auth_group")->paginate(5);       ///query("select * from auth_group"); 
        $this->assign("rolelst", $rolelst); 

        return $this->fetch(); 
    }
    //添加
    public function add_role()
    {
        // echo input('role');
        // echo input('state');
        $status = input("status");
        if($status == "on") $status = 1; else $status = 0;
        if(request()->post())
        {
            $result = Db::execute("insert into auth_group(title, status, rules) values(? , ? , ?)", [input('title'), $status, input('rules')]);  
            if($result == 1)
            {
                return $this->success("添加角色成功", "admin/sysmg/role_lst");
            }
            else
            {
                return $this->error("添加角色失败", "admin/sysmg/role_lst");
            }
        }
        return $this->fetch(); 
    }
    //删除
    public function del_role($id)
    {
        $res = Db::name("auth_group")->where("id=".$id)->delete();
        if($res)
        {
            $this->success("删除角色成功");
        }
        else
        {
            $this->error("删除角色失败"); 
        }

    }
    //修复
    public function update_role()
    {
        if(request()->post())
        {
            $id = input("id");
            $title = input('title'); 
            $status = input('status'); 
            $rules = input('rules'); 
            if($status == "on") $status = 1; else $status = 0;

            $res = Db::name("auth_group")->where("id", $id)->update(array(
                'title' => $title,
                'status' => $status, 
                'rules' => $rules,
            )); 
            // echo $res;
            if($res){
                $this->success("更新角色成功"); 
            }else{
                $this->error("更新角色失败"); 
            }
        }else{
            $id = input("id");
            $view = Db::name("auth_group")->where("id", $id)->select(); 
            // print_r($view);
            $this->assign("view", $view[0]);
        }
        
        return $this->fetch();   

    }


    // 权限列表  ---------------------------------------------------------------------------------------------------------------
    public function rule_lst() 
    {
        $rulelst = Db::name("auth_rule")->paginate(5);
        $this->assign("rulelst", $rulelst); 
        return $this->fetch(); 
    }

    public function add_rule()
    {
        if(request()->post())
        {
            $name = input('name'); 
            // echo $name; 
            $title = input('title'); 
            // echo $title;
            $status = input('status'); 
            if($status == "on") $status = 1; else $status = 0;
            // echo $status;
            
            $result = Db::execute("insert into auth_rule(name, title, status) values(?, ?, ?)", [$name, $title, $status]);
            if($result == 1)
            {
                return $this->success('添加成功', 'add_rule');
            } 
        }
        return $this->fetch();
    }

    public function update_rule()
    {
        if(request()->post())
        {
            $id = input("id");
            $name = input('name'); 
            $title = input('title'); 
            $status = input('status'); 
            if($status == "on") $status = 1; else $status = 0;
            $data = array(
                'name' => $name, 
                'title' => $title, 
                'status' => $status,
            ); 
            $res = Db::name("auth_rule")->where("id=" . $id)->update($data);
            if($res == 1)
            {
                $this->success("更新成功", "admin/sysmg/rule_lst"); 
            }
        }
        $id = input('id'); 
        $admin = Db::name("auth_rule")->where("id=" . $id)->select();
        if(!$admin){
            $this->error("管理员不存在 ... ");
        } 
        $this->assign("view", $admin[0]);
        return $this->fetch();         
    }

    public function del_rule($id)
    {
        $res = Db::name("auth_rule")->where("id=" . $id)->delete(); 
        if($res == 1)
        {
            $this->success("删除成功", "admin/sysmg/rule_lst");
        } 
        else 
        {
            $this->error("删除失败", "admin/sysmg/rule_lst");
        }
    }


    // 网站登录日志  ------------------------------------------------------------------------------------------------------------
    public function log()
    {
        $res = Db::name("access_log")->paginate(10); 
        if(!$res)
        {
            $this->error("加载日志失败 ... ");
        }
        $this->assign("view", $res); 
        return $this->fetch(); 
    }

    //网站基本信息配置 
    public function web_setting()
    {
        if(request()->post())
        {
            // echo input("post.");
            $res = Db::name("web_info")->where("id", 1)->update(input("post."));
            if($res){
                $this->success("更新信息成功  ... ");
            }
            else{
                $this->error("更新信息失败  ... "); 
            }
        }
        else
        {
            $view = Db::name("web_info")->select();   
            $this->assign("view", $view[0]);
        }
        return $this->fetch(); 
    }

}