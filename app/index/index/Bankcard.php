<?php
namespace app\index\index;

use ext\DBS;
use ext\Help;
use ext\UserAccess;
use support\Request;

class Bankcard
{

    /*@@index@@*/
    public function index(Request $request)
    {

        $reJson = [
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

    /*@@my@@*/
    public function my(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "请先登录");
        }

        $start = $request->get("per_page");
        $limit = 12;
        $fm = DBS::MM("index", "Bankcard");
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

        $reJson = [
            "data" => $redata,
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

    /*@@add@@*/
    public function add(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "请先登录");
        }

        $id = intval($request->get("id"));
        $data = [];
        if ($id) {
            $fm = DBS::MM("index", "Bankcard");
            $data = $fm->find($id);

            if (empty($row) || $row->userid != $ssuserid) {
                return Help::success(1, "暂无权限");
            }

        }
        $redata = [
            "error" => 0,
            "message" => "success",
            "data" => $data,
        ];

        $reJson = [
            "data" => $redata,
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

    /*@@save@@*/
    public function save(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "请先登录");
        }

        $id = intval($request->post("id"));
        $data = [];
        $fm = DBS::MM("index", "Bankcard");
        $indata = [];
        //处理发布内容

        $indata["userid"] = intval($request->post("userid", "0"));
        $indata["status"] = intval($request->post("status", "0"));
        $indata["yhk_name"] = $request->post("yhk_name", "");
        $indata["yhk_haoma"] = $request->post("yhk_haoma", "");
        $indata["yhk_huming"] = $request->post("yhk_huming", "");
        $indata["telephone"] = $request->post("telephone", "");
        $indata["yhk_address"] = $request->post("yhk_address", "");
        $indata["paytype"] = $request->post("paytype", "");
        if ($id) {
            $row = $fm->find($id);

            if (empty($row) || $row->userid != $ssuserid) {
                return Help::success(1, "暂无权限");
            }

        }
        if ($id) {

            $fm->where("id", $id)->update($indata);
        } else {
            $indata["userid"] = $ssuserid;

            $indata["status"] = 0;
            $id = $fm->insertGetId($indata);
        }

        $redata = [
            "error" => 0,
            "message" => "保存成功",
            "insert_id" => $id,
        ];

        $reJson = [
            "data" => $redata,
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

    /*@@status@@*/
    public function Status(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "请先登录");
        }

        $id = $request->get("id");
        $fm = DBS::MM("index", "Bankcard");
        $row = $fm->where("id", $id)->first();

        if (empty($row) || $row->userid != $ssuserid) {
            return Help::success(1, "暂无权限");
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

        $reJson = [
            "data" => $redata,
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

    /*@@delete@@*/
    public function delete(Request $request)
    {

        $ssuserid = UserAccess::checkAccess($request);
        if (!$ssuserid) {
            return Help::success(1000, "请先登录");
        }

        $id = $request->get("id");
        $fm = DBS::MM("index", "Bankcard");
        $row = $fm->find($id);

        if (empty($row) || $row->userid != $ssuserid) {
            return Help::success(1, "暂无权限");
        }

        $row->status = 11;
        $row->save();
        $redata = [
            "error" => 0,
            "message" => "success",
        ];

        $reJson = [
            "data" => $redata,
            "error" => 0,
            "message" => "success",
        ];
        return json($reJson);

    }

}
