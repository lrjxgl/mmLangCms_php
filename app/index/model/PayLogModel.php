<?php
namespace app\index\model;
use support\Model;
use ext\Help;
use ext\DBS; 
class PayLogModel extends Model{
	 
	protected $table="pay_log";
	protected $primaryKey = "id";
	const CREATED_AT= null;
	const UPDATED_AT= null;  
	public  function Dselect($list){
		if(empty($list)) return $list;
		foreach($list as &$v){
			if(isset($v->imgurl)){
				$v->imgurl=Help::images_site($v->imgurl);
			}
			$v->timeago=Help::timeago($v->createtime);
		}
		return $list; 
	}
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return [];
		$list=$this->whereIn("id",$ids)->selectRaw($fields)->get();
		$list=$this->Dselect($list);
		$reList=[];
		if($list){
			foreach($list as $v){
				$reList[$v->id]=$v;
			}
		}
		return $reList;
	}
	
}