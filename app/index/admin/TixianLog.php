<?php
namespace app\index\admin;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class TixianLog
{
	
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","TixianLog");
        $where=" 1 ";
		$list=$fm
                ->offset($start)
                ->limit($limit)
                ->whereRaw($where)
				->orderBy("id","desc")
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
        

        $id=intval($request->get("id"));
        $data=[];
        if($id){
            $fm=DBS::MM("index","TixianLog");
            $data=$fm->find($id);
            
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
       

        $id=intval($request->post("id"));
        $data=[];
        $fm=DBS::MM("index","TixianLog");
        $indata=[];
        //处理发布内容
        
$indata["siteid"]=intval($request->post("siteid","0"));
$indata["order_id"]=intval($request->post("order_id","0"));
$indata["content"]=$request->post("content","");
$indata["admin_id"]=intval($request->post("admin_id","0"));
        if($id){
            $row=$fm->find($id);
            
        }
        if($id){
            
            $fm->where("id",$id)->update($indata);
        }else{       
            
            $indata["createtime"]=date("Y-m-d H:i:s");
            
			
            $id=$fm->insertGetId($indata);
        }
      
       
        $redata=[
            "error" => 0, 
            "message" => "保存成功",
            "insert_id"=>$id
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
		

        $id=$request->get("id");
        $fm=DBS::MM("index","TixianLog");
        $row=$fm->find($id); 
        
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