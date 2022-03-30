<?php
namespace app\index\admin;

use ext\DBS;
use ext\Help;
use support\Request;
use ext\AdminAccess;
class Login{
    
    /*@@index@@*/
    public function index(Request $request){
        return Help::success(0,"success");
    }
    /*@@loginsave@@*/
    public function loginSave(Request $request){
        $username=$request->post("username","");
        $password=$request->post("password","");
        $fm=DBS::MM("index","admin");
        $row=$fm->where("username",$username)->first();
        if(empty($row)){
            return Help::success(0,"用户不存在");
        }
        if($row->password!=Help::umd5($password.$row->salt)){
            return Help::success(0,"密码出错");
        }
        $token=AdminAccess::setToken($row->id,$row->password);
        $reJson=[];
        $reJson["error"]=0;
        $reJson["message"]="登录成功";
        $reJson["data"]=$token;
        return json($reJson);
    }

}