<?php
namespace app\index\admin;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class UserExpand
{
	
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","UserExpand");
        $where=" 1 ";
		$list=$fm
                ->offset($start)
                ->limit($limit)
                ->whereRaw($where)
				->orderBy("uid","desc")
                ->get();
        $list=$fm->Dselect($list);
        $rscount=$fm->whereRaw($where)->count();
        $per_page=$start+$limit;
        $per_page=$per_page>$rscount?0:$per_page;
        $redata=[
            "error" => 0, 
            "message" => "success",
            "list"=>$list,
            "per_page"=>$per_page,
            "rscount"=>$rscount

        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

         
		   
    }

    /*@@add@@*/
	public function add(Request $request){
        

        $uid=intval($request->get("uid"));
        $data=[];
        if($uid){
            $fm=DBS::MM("index","UserExpand");
            $data=$fm->find($uid);
            
        }
        $redata=[
            "error" => 0, 
            "message" => "success",
            "data"=>$data 
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);
      
    } 
    
	
    /*@@save@@*/
	public function save(Request $request){
       

        $uid=intval($request->post("uid"));
        $data=[];
        $fm=DBS::MM("index","UserExpand");
        $indata=[];
        //处理发布内容
        
$indata["content"]=$request->post("content","");
        if($uid){
            $row=$fm->find($uid);
            
        }
        if($uid){
            
            $fm->where("uid",$uid)->update($indata);
        }else{       
            
            
            
			
            $uid=$fm->insertGetId($indata);
        }
      
       
        $redata=[
            "error" => 0, 
            "message" => "保存成功",
            "insert_id"=>$uid
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@delete@@*/
    public function delete(Request $request){
		

        $uid=$request->get("uid");
        $fm=DBS::MM("index","UserExpand");
        $row=$fm->find($uid); 
        
        $row->status=11;
        $row->save();
        $redata=[
            "error" => 0, 
            "message" => "success"
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }
      
}

?>