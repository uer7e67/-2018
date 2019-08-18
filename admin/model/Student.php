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

    public function save($data)
    {
        // $sql = ""; 
        // Db::execute(); 
        $resule = Db::name("student")->insert($data); 
        if($resule == 1)
            return 1 ;
        else
            return 0;
    }
    

}