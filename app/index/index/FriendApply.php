<?php
namespace app\index\index;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class FriendApply
{
	
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","FriendApply");
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

	/*@@list@@*/    
    public function list(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","FriendApply");
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

	/*@@show@@*/
    public function show(Request $request){
        $id=$request->get("id");
        $fm=DBS::MM("index","FriendApply");
        $data=$fm->where("id",$id)->first();
        if(empty($data) || $data->status >1){
            return Help::success(1,"数据不存在");
        }
        $data->imgurl=Help::images_site($data->imgurl);
        $author=DBS::MM("index","user")->get($data->userid);
        $redata=[
            "error" => 0, 
            "message" => "success",
            "data"=>$data,
            "author"=>$author 
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);
      
    } 

	/*@@my@@*/    
    public function my(Request $request)
    {
		
			$ssuserid=UserAccess::checkAccess($request); 
			if(!$ssuserid){
				return Help::success(1000,"请先登录");
			}
		
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","FriendApply");
        $where=" 1 ";
		$where.=" AND userid=".$ssuserid;
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
        
			$ssuserid=UserAccess::checkAccess($request); 
			if(!$ssuserid){
				return Help::success(1000,"请先登录");
			}
		
        $id=intval($request->get("id"));
        $data=[];
        if($id){
            $fm=DBS::MM("index","FriendApply");
            $data=$fm->find($id);
            
			if(empty($row) || $row->userid!=$ssuserid){
				return Help::success(1,"暂无权限");
			}
		

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
       
			$ssuserid=UserAccess::checkAccess($request); 
			if(!$ssuserid){
				return Help::success(1000,"请先登录");
			}
		
        $id=intval($request->post("id"));
        $data=[];
        $fm=DBS::MM("index","FriendApply");
        $indata=[];
        //处理发布内容
        
$indata["userid"]=intval($request->post("userid","0"));
$indata["touserid"]=intval($request->post("touserid","0"));
$indata["description"]=$request->post("description","");
        if($id){
            $row=$fm->find($id);
            
			if(empty($row) || $row->userid!=$ssuserid){
				return Help::success(1,"暂无权限");
			}
		

        }
        if($id){
            
            $fm->where("id",$id)->update($indata);
        }else{       
            $indata["userid"]=$ssuserid;
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
		
			$ssuserid=UserAccess::checkAccess($request); 
			if(!$ssuserid){
				return Help::success(1000,"请先登录");
			}
		
        $id=$request->get("id");
        $fm=DBS::MM("index","FriendApply");
        $row=$fm->find($id); 
        
			if(empty($row) || $row->userid!=$ssuserid){
				return Help::success(1,"暂无权限");
			}
		

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