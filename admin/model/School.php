<?php
namespace app\admin\model;
use think\Model;
use think\Db; 

class School extends Model{
    //初始化  
    protected function initialize()
    {
        parent::initialize();
    }

    //录入学校信息
    public function save_school_info($data)
    {
        //判断编号是否重合 
        $id = $data["id"];
        $res1 = Db::name("school")->where("id", $id)->find();
        if($res1 != null)
        {
            return 3;
        }
        $res2 = Db::name("school")->insert($data); 
        if($res2 == 1)
        {
            return 1; 
        }
        else
        {
            return 2;
        }

    }
    //获取学校教师人数  
    public function get_teacher_num()
    {

    }

    //获取学校信息    
    public function get_school_info()
    {
        
    }

    // 获取学生人数   
    public function get_student_num()
    {
        
    }
}