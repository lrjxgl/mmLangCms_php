<?php
namespace app\forum\admin;
use support\Request;
use support\Db;
use ext\DBS;
class Forum
{ 
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=4;
        $fm=DBS::MM("forum","Forum");
        $where="status in(0,1,2) ";
		$list=$fm
                ->offset($start)
                ->limit($limit)
                ->whereRaw($where)
                ->get();
        $list=$fm->Dselect($list);
        if(!empty($list)){
            $gids=[];
            $catids=[];
            foreach($list as $v){
                $gids[]=$v["gid"];
                $catids[]=$v["catid"];
            }
            $groups=DBS::MM("forum","ForumGroup")->getListByIds($gids);
            $cats=DBS::MM("forum","ForumCategory")->getListByIds($catids);
            foreach($list as $k=>$v){
                $v["group"]=$groups[$v["gid"]];
                $v["cat"]=$cats[$v["catid"]];
                $list[$k]=$v;
            }
        }
        $rscount=$fm->whereRaw($where)->count();
        $per_page=$start+$limit;
        $per_page=$per_page>$rscount?0:$per_page;
        $redata=[
            "error" => 0, 
            "message" => "ok",
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
        $id=$request->get("id");
        $fm=DBS::MM("forum","Forum");
        $data=$fm->where("id",$id)->first();
        $redata=[
            "error" => 0, 
            "message" => "ok",
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
        $fm=DBS::MM("forum","Forum");
        $fm->title="aaaa";  
        $fm->save();
        $id=$fm->id;
        $redata=[
            "error" => 0, 
            "message" => "save ok",
            "insert_id"=>$id
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
        $id=$request->get("id");
        $fm=DBS::MM("forum","Forum");
        $row=$fm->where("id",$id)->first();
        if($row->status==1){
            $status=2;
        }else{
            $status=1;
        }
        $up=$fm->find($id);
        $up->status=$status;
        $up->save();
        $redata=[
            "error" => 0, 
            "message" => "ok",
            "status"=>$status,
            "row"=>$row
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
        $id=$request->get("id");
       $fm=DBS::MM("forum","Forum");
        
        $row=$fm->where("id",$id)->first();
        if($row->isrecommend==1){
            $isrecommend=0;
        }else{
            $isrecommend=1;
        }
         
        $row->isrecommend=$isrecommend;
        $row->save();
        $redata=[
            "error" => 0, 
            "message" => "ok",
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
        $id=$request->get("id");
        $fm=DBS::MM("forum","Forum");
        $up=$fm->find($id);
        $up->status=11;
        $up->save();
        $redata=[
            "error" => 0, 
            "message" => "ok"
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