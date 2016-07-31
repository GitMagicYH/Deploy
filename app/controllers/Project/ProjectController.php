<?php
namespace Project;

class ProjectController
{
    public function settingListAction()
    {
    	$list = Project::getList();
        $sourceMap = Project::getSourceMap();
        return \View::make("project.setting", array("list" => $list, "sourceMap" => $sourceMap));
    }

    public function deployListAction() {
    	$list = Project::getList();
        $sourceMap = Project::getSourceMap();
    	return \View::make("project.deploy", array("list" => $list, "sourceMap" => $sourceMap));
    }

    public function editAction() {
    	$id = \Input::get("id", "");
    	$param = $this->getUpdateParam();
        $now = date("Y-m-d H:i:s");
    	if (empty($id)) {	// new project
    		$param["create_time"] = $now;
    		$param["update_time"] = $now;
    		Project::insert($param);
    	} else {	// new project
    		$param["update_time"] = $now;
    		Project::update($param, array("id" => $id));
    	}
    	return json_encode(array("code" => 0));
    }

    public function deployAction() {
    	$id = \Input::get("id", "");
    	try {
    		if (empty($id)) {
    			throw new \Exception("Invalid id", 1);
    		}
    		Project::deploy($id);
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return json_encode(array("code" => 1, "msg" => $msg));
    	}
    	return json_encode(array("code" => 0));
    }

    public function rollbackAction() {
    	$id = \Input::get("id", "");
    	try {
    		if (empty($id)) {
    			throw new \Exception("Invalid id", 1);
    		}
    		Project::rollback($id);
    	} catch (\Exception $e) {
    		$msg = $e->getMessage();
    		return json_encode(array("code" => 1, "msg" => $msg));
    	}
    	return json_encode(array("code" => 0));
    }

    private function getUpdateParam() {
    	$param["name"] = \Input::get("name", "");
    	$param["type"] = \Input::get("type", "");
    	$param["source_url"] = \Input::get("source_url", "");
    	$param["target_path"] = \Input::get("target_path", "");
        $param["host_list"] = \Input::get("host_list", "");
    	$param["user_name"] = \Input::get("user_name", "");
    	$param["pswd"] = \Input::get("pswd", "");
    	return $param;
    }
}
