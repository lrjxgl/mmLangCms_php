<?php
namespace app\index\index;

use ext\DBS;
use ext\Help;
use ext\UserAccess;
use support\Request;

class UserAddress
{

    /*@@index@@*/
    public function index(Request $request)
    {
        $start = $request->get("per_page");
        $limit = 12;
        $fm = DBS::MM("index", "UserAddress");
        $where = "status in(0,1,2) ";
        $list = $fm
            ->offset($start)
            ->limit($limit)
            ->whereRaw($where)
            ->orderBy("id", "desc")
            ->get();
        $list = $fm->Dselect($list);
        $rscount = $fm->whereRaw($where)->count();
        $per_page = $start + $limit;
        $per_page = $per_page > $rscount ? 0 : $per_page;
        $redata = [
            "error" => 0,
            "message" => "success",
            "list" => $list,
            "per_page" => $per_page,
            "rscount" => $rscount,

        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);


    }

    /*@@list@@*/
    function list(Request $request) {
        $start = $request->get("per_page");
        $limit = 12;
        $fm = DBS::MM("index", "UserAddress");
        $where = "status in(0,1,2) ";
        $list = $fm
            ->offset($start)
            ->limit($limit)
            ->whereRaw($where)
            ->orderBy("id", "desc")
            ->get();
        $list = $fm->Dselect($list);
        $rscount = $fm->whereRaw($where)->count();
        $per_page = $start + $limit;
        $per_page = $per_page > $rscount ? 0 : $per_page;
        $redata = [
            "error" => 0,
            "message" => "success",
            "list" => $list,
            "per_page" => $per_page,
            "rscount" => $rscount,

        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);


    }

    /*@@show@@*/
    public function show(Request $request)
    {
        $id = $request->get("id");
        $fm = DBS::MM("index", "UserAddress");
        $data = $fm->where("id", $id)->first();
        if (empty($data) || $data->status > 1) {
            return Help::success(1, "???????????????");
        }
        $data->imgurl = Help::images_site($data->imgurl);
        $author = DBS::MM("index", "user")->get($data->userid);
        $redata = [
            "error" => 0,
            "message" => "success",
            "data" => $data,
            "author" => $author,
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

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "????????????");
        }

        $start = $request->get("per_page");
        $limit = 12;
        $fm = DBS::MM("index", "UserAddress");
        $where = "status in(0,1,2) ";
        $where .= " AND userid=" . $ssuserid;
        $list = $fm
            ->offset($start)
            ->limit($limit)
            ->whereRaw($where)
            ->orderBy("id", "desc")
            ->get();
        $list = $fm->Dselect($list);
        $rscount = $fm->whereRaw($where)->count();
        $per_page = $start + $limit;
        $per_page = $per_page > $rscount ? 0 : $per_page;
        $redata = [
            "error" => 0,
            "message" => "success",
            "list" => $list,
            "per_page" => $per_page,
            "rscount" => $rscount,

        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);


    }

    /*@@add@@*/
    public function add(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "????????????");
        }

        $id = intval($request->get("id"));
        $data = [];
        if ($id) {
            $fm = DBS::MM("index", "UserAddress");
            $data = $fm->find($id);

            if (empty($row) || $row->userid != $ssuserid) {
                return Help::success(1, "????????????");
            }

        }
        $redata = [
            "error" => 0,
            "message" => "success",
            "data" => $data,
        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@save@@*/
    public function save(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "????????????");
        }

        $id = intval($request->post("id"));
        $data = [];
        $fm = DBS::MM("index", "UserAddress");
        $indata = [];
        //??????????????????

        $indata["userid"] = intval($request->post("userid", "0"));
        $indata["address"] = $request->post("address", "");
        $indata["telephone"] = $request->post("telephone", "");
        $indata["truename"] = $request->post("truename", "");
        $indata["status"] = intval($request->post("status", "0"));
        $indata["zip_code"] = $request->post("zip_code", "");
        $indata["lastid"] = intval($request->post("lastid", "0"));
        $indata["province_id"] = intval($request->post("province_id", "0"));
        $indata["city_id"] = intval($request->post("city_id", "0"));
        $indata["town_id"] = intval($request->post("town_id", "0"));

        $dids = [$indata["province_id"], $indata["city_id"], $indata["town_id"]];
        $pct = DBS::MM("index", "District")->getListByIds($dids);
        $indata["pct_address"] = $pct[$indata["province_id"]]["name"] . $pct[$indata["city_id"]]["name"] . $pct[$indata["town_id"]]["name"] . $indata["address"];
        $indata["lat"] = floatval($request->post("lat", "0"));
        $indata["lng"] = floatval($request->post("lng", "0"));
        if ($id) {
            $row = $fm->find($id);

            if (empty($row) || $row->userid != $ssuserid) {
                return Help::success(1, "????????????");
            }

        }
        $indata["userid"] = $ssuserid;
        $indata["createtime"] = date("Y-m-d H:i:s");

        $indata["status"] = 0;
        $id = $fm->insertGetId($indata);

        $redata = [
            "error" => 0,
            "message" => "????????????",
            "insert_id" => $id,
        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@status@@*/
    public function Status(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "????????????");
        }

        $id = $request->get("id");
        $fm = DBS::MM("index", "UserAddress");
        $row = $fm->where("id", $id)->first();

        if (empty($row) || $row->userid != $ssuserid) {
            return Help::success(1, "????????????");
        }

        if ($row->status == 1) {
            $status = 2;
        } else {
            $status = 1;
        }
        $up = $fm->find($id);
        $up->status = $status;
        $up->save();
        $redata = [
            "error" => 0,
            "message" => "success",
            "status" => $status,
        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

    /*@@delete@@*/
    public function delete(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "????????????");
        }

        $id = $request->get("id");
        $fm = DBS::MM("index", "UserAddress");
        $row = $fm->find($id);

        if (empty($row) || $row->userid != $ssuserid) {
            return Help::success(1, "????????????");
        }

        $row->status = 11;
        $row->save();
        $redata = [
            "error" => 0,
            "message" => "success",
        ];
        
	$reJson=[
		"data"=>$redata,
		"error"=>0,
		"message"=>"success"
    ];
	return json($reJson);

    }

}
