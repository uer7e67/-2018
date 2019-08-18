<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route; 

// Route::rule("admin/index", "admin/admin/index"); 
// Route::rule("admin/window", "admin/admin/window");
// Route::rule("admin/basecode", "admin/admin/basecode");


// Route::rule("admin/schoolmg", "admin/admin/schoolmg");
// Route::rule("admin/teacherxmg", "admin/admin/teacherxmg");
// Route::rule("admin/teachermg", "admin/admin/teachermg");
// Route::rule("admin/studentmg", "admin/admin/studentmg");


return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
