<?php
namespace Project;

class Project
{
	const TABLE = "project";
    const SOURCE_SVN = 0;
    const SOURCE_GIT = 1;

    private static $sourceMap = array(
            SOURCE_SVN => "SVN",
            SOURCE_GIT => "Git",
        );

	// get the list of project
    public static function getList() {
    	$list = \DbManager::queryTable(self::TABLE);
    	$list = self::buildJson($list);
    	return $list;
    }

    public static function insert($param) {
    	return \DBManager::insert(self::TABLE, $param);
    }

    public static function update($param, $where) {
    	return \DBManager::update(self::TABLE, $param, $where);
    }

    public static function getSourceMap() {
        return self::$sourceMap;
    }

    public static function deploy($id) {

    }

    public static function rollback($id) {

    }

    private static function buildJson($list) {
    	foreach ($list as $key => &$row) {
    		$row->json = json_encode($row);
    	}
    	return $list;
    }
}
