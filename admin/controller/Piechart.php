<?php  
namespace app\admin\controller; 
use think\Controller; 
use think\Db; 



class Piechart extends Controller
{
    public function charts()
    {
        $school = Db::name("school")->select(); 
        $this->assign("school", $school);
        if(request()->post())
        {
            $school_id = input("school_id"); 
            $res1 = Db::table('student')->where("school_id=" . $school_id)->count();
            $res2 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='智力残疾'")->count();
            $res3 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='视力残疾'")->count();
            $res4 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='听力残疾'")->count();
            $res5 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='其他残疾'")->count();
            //
            $pt1 = $res2 / $res1; 
            $pt2 = $res3 / $res1; 
            $pt3 = $res4 / $res1; 
            $pt4 = $res5 / $res1;

            $view = array(
                'pt1' => $pt1,
                'pt2' => $pt2,
                'pt3' => $pt3,
                'pt4' => $pt4, 
            ); 

            $this->assign("view", $view);
        }
        else
        {
            $school_id = "1001"; 
            $res1 = Db::table('student')->where("school_id=" . $school_id)->count();
            $res2 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='智力残疾'")->count();
            $res3 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='视力残疾'")->count();
            $res4 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='听力残疾'")->count();
            $res5 = Db::table('student')->where("school_id=" . $school_id. " AND deformity_type='其他残疾'")->count();
            //
            $pt1 = $res2 / $res1; 
            $pt2 = $res3 / $res1; 
            $pt3 = $res4 / $res1; 
            $pt4 = $res5 / $res1;

            $view = array(
                'pt1' => $pt1,
                'pt2' => $pt2,
                'pt3' => $pt3,
                'pt4' => $pt4, 
            ); 

            $this->assign("view", $view);
        }
        return $this->fetch();
    }
}