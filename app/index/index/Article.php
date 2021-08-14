<?php
namespace app\index\index;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class Article
{
	
	/*@@index@@*/    
    public function index(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","Article");
        $where="status in(0,1,2) ";
        $catid=intval($request->get("catid"));
        if($catid){
            $cids=DBS::MM("index","category")->id_family($catid);
            $where.=" AND catid in(".Help::_implode($cids).") ";
        } 
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
        //获取分类
        $catList=DBS::MM("index","category")->WhereRaw("status=1 AND pid=0 AND tablename='article'")->get();

        $redata=[
            "error" => 0, 
            "message" => "success",
            "list"=>$list,
            "per_page"=>$per_page,
            "rscount"=>$rscount,
            "catList"=>$catList

        ];
		return json($redata); 
         
		   
    }

	/*@@list@@*/    
    public function list(Request $request)
    {
	    $start=$request->get("per_page");
        $limit=12;
        $fm=DBS::MM("index","Article");
        $where="status in(0,1,2) ";
        $catid=intval($request->get("catid"));
        $cat=[];
        if($catid){
            $cids=DBS::MM("index","category")->id_family($catid);
            $where.=" AND catid in(".Help::_implode($cids).") ";
            $cat=DBS::MM("index","category")->Where("catid",$catid)->First();
        }
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
        //分类
        $catList=DBS::MM("index","category")->Children($catid,"article",1); 
        $redata=[
            "error" => 0, 
            "message" => "success",
            "list"=>$list,
            "per_page"=>$per_page,
            "rscount"=>$rscount,
            "cat"=>$cat,
            "catList"=>$catList

        ];
		return json($redata); 
         
		   
    }

	/*@@show@@*/
    public function show(Request $request){
        $id=$request->get("id");
        $fm=DBS::MM("index","Article");
        $data=$fm->where("id",$id)->first();
        if(empty($data) || $data->status >1){
            return Help::success(1,"数据不存在");
        }
        $data->imgurl=Help::images_site($data->imgurl);
        $data->content=DBS::MM("index","articleData")->where("id",$id)->value("content");
        $author=DBS::MM("index","user")->get($data->userid);
        $redata=[
            "error" => 0, 
            "message" => "success",
            "data"=>$data,
            "author"=>$author 
        ];
		return json($redata);       
    } 
      
}

?>