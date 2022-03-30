<?php
namespace app\forum\admin;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class ForumGroup
{
	
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("forum","ForumGroup");
        $where="status in(0,1,2) ";
		$list=$fm
                ->offset($start)
                ->limit($limit)
                ->whereRaw($where)
				->orderBy("gid","desc")
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
        

        $gid=intval($request->get("gid"));
        $data=[];
        if($gid){
            $fm=DBS::MM("forum","ForumGroup");
            $data=$fm->find($gid);
            
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
       

        $gid=intval($request->post("gid"));
        $data=[];
        $fm=DBS::MM("forum","ForumGroup");
        $indata=[];
        //处理发布内容
        
$indata["title"]=$request->post("title","");
$indata["imgurl"]=$request->post("imgurl","");
$indata["description"]=$request->post("description","");
$indata["orderindex"]=intval($request->post("orderindex","0"));
$indata["status"]=intval($request->post("status","0"));
$indata["topic_num"]=intval($request->post("topic_num","0"));
        if($gid){
            $row=$fm->find($gid);
            
        }
        if($gid){
            
            $fm->where("gid",$gid)->update($indata);
        }else{       
            
            
            
			
            $gid=$fm->insertGetId($indata);
        }
      
       
        $redata=[
            "error" => 0, 
            "message" => "保存成功",
            "insert_id"=>$gid
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@status@@*/
    public function Status(Request $request){
		

        $gid=$request->get("gid");
        $fm=DBS::MM("forum","ForumGroup");
        $row=$fm->where("gid",$gid)->first();
		
        if($row->status==1){
            $status=2;
        }else{
            $status=1;
        }
        $up=$fm->find($gid);
        $up->status=$status;
        $up->save();
        $redata=[
            "error" => 0, 
            "message" => "success",
            "status"=>$status
        ];
		
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@recommend@@*/
    public function recommend(Request $request){
		

        $gid=$request->get("gid");
       $fm=DBS::MM("forum","ForumGroup");
        
        $row=$fm->where("gid",$gid)->first();
		
        if($row->isrecommend==1){
            $isrecommend=0;
        }else{
            $isrecommend=1;
        }
         
        $row->isrecommend=$isrecommend;
        $row->save();
        $redata=[
            "error" => 0, 
            "message" => "success",
            "isrecommend"=>$isrecommend
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
		

        $gid=$request->get("gid");
        $fm=DBS::MM("forum","ForumGroup");
        $row=$fm->find($gid); 
        
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
    /*@@stat@@ */
    public function stat(Request $request){
        $fg=DBS::MM("forum","ForumGroup");
        $ff=DBS::MM("forum","Forum");
        $fc=DBS::MM("forum","ForumComment");
        $groupList=$fg->Where("status",1)->get();
        if(!empty($groupList)){
            foreach($groupList as $g){
                $fnum=$ff->WhereRaw("gid=".$g->gid." AND status in(0,1) ")->count();
                $cnum=$fc->WhereRaw("gid=".$g->gid." AND status in(0,1) ")->count();
                $fg->Where("gid",$g->gid)->update([
                    "topic_num"=>$fnum,
                    "comment_num"=>$cnum
                ]);
            }
        }
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