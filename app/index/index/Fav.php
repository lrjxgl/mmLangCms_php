<?php
namespace app\index\index;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class Fav
{ 

    /*
    @@my@@
    */
    public function my(Request $request){
        $userid=UserAccess::checkAccess($request);  
        if($userid==0){
            return Help::success(1,"请先登录");
        }
       
        $tablename=$request->get("tablename","");
        $where=[
            ["userid",$userid],
           
            ["tablename",$tablename]

        ];
        $ids=DBS::MM("index","fav")->where($where)->pluck("objectid");
        $list=[]; 
        switch($tablename){
            case "article":
                $list=DBS::MM("index","article")->getListByIds($ids);
                break;
            case "forum":
                $list=DBS::MM("forum","forum")->getListByIds($ids);
                break;    
        }
        $reData=[
            "error"=>0,
            "message"=>"success",
            "list"=>$list

        ]; 
        
	$reJson=[
		"data"=>$reData,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);


    }
	 /*@@get@@*/
     public function get(Request $request){
        $userid=UserAccess::checkAccess($request);  
        if($userid==0){
            return Help::success(1,"请先登录");
        }
        $objectid=intval($request->get("objectid"));
        $tablename=$request->get("tablename","");
        $where=[
            ["userid",$userid],
            ["objectid",$objectid],
            ["tablename",$tablename]

        ];
        $action="delete";
        $row=DBS::MM("index","fav")->where($where)->first();
        if(!empty($row)){
            $action="add";
        }
        $reData=[
            "error"=>0,
            "message"=>"success",
            "action"=>$action

        ];
        
	$reJson=[
		"data"=>$reData,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }
    /*@@toggle@@*/
    public function toggle(Request $request){
        $userid=UserAccess::checkAccess($request);  
        if($userid==0){
            return Help::success(1,"请先登录");
        }
        $objectid=intval($request->get("objectid"));
        $tablename=$request->get("tablename","");
        $where=[
            ["userid",$userid],
            ["objectid",$objectid],
            ["tablename",$tablename]

        ];
        $indata=[
            "userid"=>$userid,
            "objectid"=>$objectid,
            "tablename"=>$tablename
        ];
        $row=DBS::MM("index","fav")->where($where)->first();
        if($row){
            $action="delete";
            DBS::MM("index","fav")->where($where)->delete();
        }else{
            DBS::MM("index","fav")->insert($indata);
            $action="add";
        }
        $reData=[
            "error"=>0,
            "message"=>"success",
            "action"=>$action

        ]; 
        
	$reJson=[
		"data"=>$reData,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    } 
 
      
}

?>