<?php
use Caravel\Database\DB;

class DBManager extends DB {
	const QUERY_RAW_SQL = '?RAW_SQL';

    public static function queryTable($tableName, $fields='*', $where='', $orderBy='', $limit=0, $offset=0, $hasCount=false, $groupBy='') {
        $bindParams = array();
        if (is_array($where)) {
            $condFields = array();
            foreach ($where as $k => $value) {
                if (!is_array($value)) $value = array($value);
                foreach ($value as $v) {
                    if ($k == self::QUERY_RAW_SQL) {
                        $condFields[] = "$v";
                    }
                    else if (strpos($v, '%')===0) {
                        $condFields[] = "{$k} like ?";
                        $bindParams[] = $v;
                    }
                    else if (strpos($v, '>=')===0) {
                        $condFields[] = "{$k}>=?";
                        $bindParams[] = substr($v, 2);
                    }
                    else if (strpos($v, '<=')===0) {
                        $condFields[] = "{$k}<=?";
                        $bindParams[] = substr($v, 2);
                    }
                    else if (strpos($v, '>')===0) {
                        $condFields[] = "{$k}>?";
                        $bindParams[] = substr($v, 1);
                    }
                    else if (strpos($v, '<')===0) {
                        $condFields[] = "{$k}<?";
                        $bindParams[] = substr($v, 1);
                    }
                    else if (strpos($v, '!=')===0) {
                        $condFields[] = "{$k}!=?";
                        $bindParams[] = substr($v, 2);
                    }
                    else {
                        $condFields[] = "{$k}=?";
                        $bindParams[] = $v;
                    }
                }
            }
            if (count($condFields) > 0) {
                $where = " WHERE " . implode(' AND ', $condFields);
            }
            else {
                $where = '';
            }
        }
        $orderBy = empty($orderBy) ? '' : "ORDER BY {$orderBy}";
        $groupBy = empty($groupBy) ? '' : "GROUP BY {$groupBy}";
        $limit = intval($limit);
        $offset = intval($offset);
        $limit = ($limit!=0 || $offset!=0) ? "LIMIT {$limit} OFFSET {$offset}" : '';
        $sql = "SELECT {$fields} FROM {$tableName} {$where} {$groupBy} {$orderBy} {$limit}";
        $result = self::select($sql, $bindParams);
        if ($hasCount) {
            $db->reconnect();
            if ($groupBy) {
                $sql = "SELECT count(*) as c FROM (SELECT 1 from {$tableName} {$where} {$groupBy}) as nestq";
            }
            else {
                $sql = "SELECT count(*) as c FROM {$tableName} {$where}";
            }
            $row = \DB::selectOne($sql, $bindParams);
            $count = $row->c;
            $result = array($count, $result);
        }
        return $result;
    }

    public static function findOneBy($tableName, $where='') {
        $list = $this->queryTable($tableName, '*', $where, '', 1);
        if (!empty($list) && !empty($list[0])) {
            return $list[0];
        }
        return false;
    }

    public static function update($tableName, $data, $where) {
        $dataFields = array();
        foreach ($data as $k => $v) {
            $dataFields[] = "`{$k}`=?";
        }
        $dataFields = implode(',', $dataFields);

        $condFields = array();
        $condParams = array();
        foreach ($where as $k => $value) {
            if (!is_array($value)) $value = array($value);
            foreach ($value as $v) {
                if ($k == self::QUERY_RAW_SQL) {
                    $condFields[] = "$v";
                }
                else if (strpos($v, '%')!==FALSE) {
                    $condFields[] = "`{$k}` like ?";
                    $condParams[] = $v;
                }
                else if (strpos($v, '>=')===0) {
                    $condFields[] = "`{$k}`>=?";
                    $condParams[] = substr($v, 2);
                }
                else if (strpos($v, '<=')===0) {
                    $condFields[] = "`{$k}`<=?";
                    $condParams[] = substr($v, 2);
                }
                else if (strpos($v, '>')===0) {
                    $condFields[] = "`{$k}`>?";
                    $condParams[] = substr($v, 1);
                }
                else if (strpos($v, '<')===0) {
                    $condFields[] = "`{$k}`<?";
                    $condParams[] = substr($v, 1);
                }
                else if (strpos($v, '!=')===0) {
                    $condFields[] = "`{$k}`!=?";
                    $condParams[] = substr($v, 2);
                }
                else {
                    $condFields[] = "`{$k}`=?";
                    $condParams[] = $v;
                }
            }
        }
        $condFields = implode(' AND ', $condFields);
        $condFields = empty($condFields) ? "" : "WHERE {$condFields}";
        $bindParams = array_merge(array_values($data), $condParams);
        $sql = "UPDATE {$tableName} SET {$dataFields} {$condFields}";
        return self::query($sql, $bindParams);
    }
}