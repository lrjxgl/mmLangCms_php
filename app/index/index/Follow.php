<?php
namespace app\index\index;
use support\Request;
use support\Db;
use ext\DBS;
use ext\UserAccess;
use ext\Help;
class Follow
{
	
    /*
    @@index@@
    我的关注
    */
    public function index(Request $request){
        $ssuserid=UserAccess::checkAccess($request); 
        if(!$ssuserid){
            return Help::success(1000,"请先登录");
        }
        $userid=intval($request->get("userid"));
        if($userid==0){
            $userid=$ssuserid;
        }
        $res=DBS::MM("index","follow")->Where("userid",$userid)->get();
        $list=[];
        if($res){
            foreach($res as $rs){
                $uids[]=$rs["t_userid"];
            }
            $list=DBS::MM("index","user")->getListByIds($uids,"userid,user_head,nickname,follow_num,followed_num");
            if(!empty($list)){
                foreach($list as &$v){
                    $v["isFollow"]=1;
                }
            }
            
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

     /*
    @@followed@@
    我的粉丝
    */
    public function followed(Request $request){
        $ssuserid=UserAccess::checkAccess($request); 
        if(!$ssuserid){
            return Help::success(1000,"请先登录");
        }
        $userid=intval($request->get("userid"));
        if($userid==0){
            $userid=$ssuserid;
        }
        $res=DBS::MM("index","followed")->Where("userid",$userid)->get();
        $list=[];
        if($res){
            foreach($res as $rs){
                $uids[]=$rs["t_userid"];
            }
            $list=DBS::MM("index","user")->getListByIds($uids,"userid,user_head,nickname,follow_num,followed_num");
            
            foreach($res as $rs){
                $v=$list[$rs["t_userid"]];
                $v["isFollow"]=$rs["status"]==2?1:0;
                $list[$rs["t_userid"]]=$v;
            }
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
	/*@@toggle@@*/    
    public function toggle(Request $request){
        $ssuserid=UserAccess::checkAccess($request); 
        if(!$ssuserid){
            return Help::success(1000,"请先登录");
        }
        $t_userid=intval($request->get("t_userid"));
        $row=DBS::MM("index","follow")->whereRaw("userid=".$ssuserid." AND t_userid=".$t_userid)->first();
        if($row){
            $isFollow=0;
            DBS::MM("index","follow")->whereRaw("userid=".$ssuserid." AND t_userid=".$t_userid)->delete();
        }else{
            $isFollow=1;
            DBS::MM("index","follow")->insert(array(
                "userid"=>$ssuserid, 
                "t_userid"=>$t_userid
            ));
        }
        $reData=[
            "error"=>0,
            "message"=>"success",
            "isFollow"=>$isFollow
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