<?php
namespace app\admin\controller; 
use think\Db; 
use think\Request; 
use think\Loader; 
use app\admin\model\Student;
use app\admin\model\Teacher;  
use app\admin\model\School; 

//数据导入 优化部分    
class Data extends  BaseController
{
//-------------------------------------基础代码管理 -------------------------------------//  
    //页面   
    public function basecode()
    {
        $list = Db::table("upload")->select();
        // print_r($list);
        $this->assign('list',$list);
        // $this->display();
        return $this->fetch();
    }
    //文件上传提交  
    public function up(Request $request)
    {
        if(request()->isPost())
        {
            $type = input("type");
            echo $type;
            // 获取表单上传文件
            $file = $request->file('excel');
            // 如果希望保持上传文件的原文件名保存，则可以使用
            echo ROOT_PATH . 'public'. DS . 'upfile' , '';
            $info = $file->move(ROOT_PATH . 'public'. DS . 'upfile' , '');
            echo $info->getSaveName(); 
            echo $info->getRealPath(); 

            // 获取下上传文件的type
            
            if ($info) {
                //信息保存到数据库中  
                $data = [
                    'name' => $info->getSaveName(),   
                    'state' => '1',
                    'type' => $type,
                    'time' => date('Y-m-d H:i:s'),
                ]; 
                Db::name("upload")->insert($data);
                //获取下id 
                $fid = Db::query("SELECT LAST_INSERT_ID()"); 
                // $code = [
                //     'ul_id' => $fid[0]['LAST_INSERT_ID()'],
                // ];
                // Db::name("code")->insert($code);
                $this->success('文件上传成功：');
            } else {
                // 上传失败获取错误信息
                $this->error($file->getError());
            }
        } 
    }
    
    //文件删除   
    public function delfile(){
        $id = input('id') ; 
        $result = Db::name('upload')->where('id',$id)->delete();
        // Db::name();
        if($result != null)
        {
            return $this->success("删除成功..."); 
        }
        $this->error("删除失败");
    }


    //预览excel 文件
     /* 导入所需的类库 同java的Import 本函数有缓存功能
     * @param string $class   类库命名空间字符串
     * @param string $baseUrl 起始路径
     * @param string $ext     导入的文件扩展名
     * @return boolean
     */
    // public static function import($class, $baseUrl = '', $ext = EXT)
    public function see_file()
    {
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        //获取文件路径
        $id = input('id'); 
        $tagName = Db::table('upload')->where('id='.$id)->select(); 
        $path = ROOT_PATH."public".DS."upfile".DS.$tagName[0]['name']; 
        //获取到sheet的个数  
        $objExcel = \PHPExcel_IOFactory::load($path);
        $sheetCount = $objExcel->getSheetCount();
        //打印下内容
        $sheet = $objExcel->getSheet(0); 
        //获取行数
        $total_rows = $sheet->getHighestRow(); 
        //获取总列数
        $total_columns = $sheet->getHighestColumn();
        
        for($j = 1; $j <= $total_rows; $j++)
        {
            for($i = 1; $j < $total_columns; $i++)
            {
                $data[$j][$i] = $sheet->getCellByColumnAndColumn()->getValue();
            }
        }   
        $data = $objExcel->getSheet(0)->toArray(); 
        $count = count($data); 
        $this->assign("data", $data);
        // print_r($sheet);
        // foreach($sheet->getRowIterator() as $row){
        //     foreach($row->getCellIterator() as $cell){
        //         $data = $cell->getValue(); 
        //         echo $data." "; 
        //     }
        //      echo '<br/>';
        // }

        
        return $this->fetch();
    }

    public function submit_db()
    {
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        //获取文件路径
        $id = input('id');

        //
        // echo $id; 
        $tagName = Db::table('upload')->where('id='.$id)->select(); 
        // print_r($tagName);
        $path = ROOT_PATH."public".DS."upfile".DS.$tagName[0]['name']; 
        //获取excel类型 
        $type = $tagName[0]['type'];
        //获取到sheet的个数  
        $objExcel = \PHPExcel_IOFactory::load($path);
        $sheetCount = $objExcel->getSheetCount();
        //打印下内容
        $sheet = $objExcel->getSheet(0); 
        //获取行数
        $data = $sheet->toArray();
        //获取行数
        $total_rows = $sheet->getHighestRow(); 
        //
        $res = 0;
        //如果是学生
        if($type == 0)
        {
            for($i=2; $i<=$total_rows; $i++)
            {
                $stu_id = $sheet->getCell("A".$i)->getValue();
                $name = $sheet->getCell("B".$i)->getValue();
                $sex = $sheet->getCell("C".$i)->getValue();
                $state = $sheet->getCell("D".$i)->getValue();
                $national = $sheet->getCell("E".$i)->getValue();
                $birthday = $sheet->getCell("F".$i)->getValue();
                $domicile = $sheet->getCell("G".$i)->getValue();
                $id_number = $sheet->getCell("H".$i)->getValue();
                $education = $sheet->getCell("I".$i)->getValue();
                $deformity_type = $sheet->getCell("J".$i)->getValue();
                $register_type = $sheet->getCell("K".$i)->getValue();
                $political_visage = $sheet->getCell("L".$i)->getValue();
                $school_id = $sheet->getCell("M".$i)->getValue();
                $enter_school_date = $sheet->getCell("N".$i)->getValue();
                $major = $sheet->getCell("O".$i)->getValue();
                $class = $sheet->getCell("P".$i)->getValue();
                $res += Db::name('student')->insert(array(
                    'stu_id' => $stu_id,
                    'name' => $name,
                    'sex' => $sex,
                    'state' => $state,
                    'national' => $national,
                    'birthday' => $birthday,
                    'domicile' => $domicile,
                    'id_number' => $id_number,
                    'education' => $education,
                    'deformity_type' => $deformity_type,
                    'register_type' => $register_type,
                    'political_visage' => $political_visage,
                    'school_id' => $school_id,
                    'enter_school_date' => $enter_school_date,
                    'major' => $major,
                    'class' => $class,
                ));
            }
        }
        //如果是老师
        if($type == 1)
        {
            for($i=2; $i<=$total_rows; $i++)
            {
                $tc_id = $sheet->getCell("A".$i)->getValue();
$name = $sheet->getCell("B".$i)->getValue();
$sex = $sheet->getCell("C".$i)->getValue();
$national = $sheet->getCell("D".$i)->getValue();
$birthday = $sheet->getCell("E".$i)->getValue();
$domicile = $sheet->getCell("F".$i)->getValue();
$id_number = $sheet->getCell("G".$i)->getValue();
$entry_date = $sheet->getCell("H".$i)->getValue();
$education = $sheet->getCell("I".$i)->getValue();
$title = $sheet->getCell("G".$i)->getValue();
$train_prifessional = $sheet->getCell("K".$i)->getValue();
$school_id = $sheet->getCell("L".$i)->getValue();
$res += Db::name("teacher")->insert(array(
                    'tc_id' => $tc_id, 
                    'name' => $name, 
                    'sex' => $sex, 
                    'national' => $national, 
                    'birthday' => $birthday,
                    'domicile' => $domicile,
                    'id_number' => $id_number,
                    'entry_date' => $entry_date, 
                    'education' => $education,
                    'title' => $title, 
                    'train_prifessional' => $train_prifessional,
                    'school_id' => $school_id, 
                ));
            }
        }

        if($res > 0){
            Db::name("upload")->where("id", $id)->update(array(
                'state' => 0,
            ));
            return $this->success("导入成功");
        }
        else{
            return $this->error("导入失败");
        }
        
    }


    // 代码管理  

    // 添加新类型的sql 
    public function codeadd()
    {
        return $this->fetch(); 
    }

    
    public function codelst()
    {
        return $this->fetch(); 
    }


    //人工录入  
    public function artificial()
    {
        return $this->fetch();
    }

    //学生录入 
    public function input_student()
    {
        if(request()->post())
        {
            print_r($_POST);
            $data = $_POST;
            $result = Db::name("student")->insert($data); 
            if($result == 1)
            {
                $this->success("录入学生成功", "admin/data/artificial");
            }
            else
            {
                $this->error("录入失败", "admin/data/input_student");
            }
        }
        return $this->fetch(); 
    }

    public function input_teacher()
    {
        if(request()->post())
        {
            print_r($_POST);
            $data = $_POST; 
            $result = Db::name("teacher")->insert($data); 
            if($result == 1)
            {
                $this->success("录入教职工成功", "admin/data/input_teacher");
            } 
            else
            {
                $this->error("录入失败", "admin/data/input_teacher");
            }
        }
        return $this->fetch(); 
    }

    public function input_school()
    {
        if(request()->post())
        {
            print_r($_POST);
            $data = $_POST; 
            $school = new School(); 
            $res = $school->save_school_info($data); 
            if($res == 1)    //成功添加 
            {
                return $this->success("添加学校成功", "admin/data/input_school"); 
            }
            if($res == 2)   //添加失败   
            {
                return $this->error("添加失败 ", "admin/data/input_school"); 
            }
            if($res == 3)   //编号已经存在 
            {   
                return $this->error("该学校编号已经存在 ", "admin/data/input_school"); 
            }
        }
        return $this->fetch();
    }


}




        // //保存到数据库中 
        // public function saveSql(Request $request)
        // {
        //     //1 获取到sql语句
        //     $sql = $request->param('sql');
        //     echo $sql; 
        //     $id = input('id');                                              ///////////////////////////////////////////////问题  id 拿不到    ，，，，  

        //     $arr = Db::query("select * from code");
        //     print_r($arr);                                                            
        //     $result = Db::execute("update code set code=? where ul_id=2",[$sql]);                                                
        //     //3 添加到数据库
        //     echo $result;   

        // }

        // if(request()->isPost())
        // {
        //     print_r($_POST);
        // }
        // $sql = $_POST;
        // echo $sql;


        // $name = '郭恩泽'; 
        // $type = '低能儿'; 
        // echo implode("", $arr);


        // $data = $objExcel->getSheet(0)->toArray();
        // print_r($data);

        // for($i = 0 ; $i < $sheetCount; $i ++){
        //     $data = $objExcel->getSheet($i)->toArray();
        //     print_r($data); 
        // }


        // $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
        // $obj_PHPExcel =$objReader->load($path, $encode = 'utf-8'); 
        // $sheetCount = $obj_PHPExcel->getSheetCount();
        // echo $sheetCount;

        // $objExcel = new \PHPExcel_IOFactory();
        // $sheetCount = $objExcel->getSheetCount()
        //显示这个excel 
        // print_r($path);
        // $PHPExcel_IOFactory::load($path); 
        // $objExcel = PHPExcel_IOFactory::load($path);   //加载文件 
        // $sheetCount = $objExcel->getSheetCount(); 

        // print_r($sheetCount);
        // for($i = 0; i < $sheetCount; $i++){
        //     $data = $objExcel->getSheet($i)->toArray();
        //     print_r($data);
        // }

        // echo $path; 
        // echo $id;    //5 
