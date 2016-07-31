<?php

class HomeController
{
    public function indexAction()
    {
    	$fileList = $this->getFileList();
        return View::make("home.index", array("list" => $fileList));
    }

    public function uploadAction() {
    	try {
    		$fileName = $_FILES["file"]["name"];
	    	$fileSize = $_FILES["file"]["size"];
	    	$uploadDir = App::getAppRoot() . "/upload";
	    	if (!is_dir($uploadDir)) {
	    		mkdir($uploadDir);
	    	}
	    	$filePath = $uploadDir . "/" . $fileName;
	    	$finalPath = $filePath;
	    	$index = 1;
	    	while (is_file($finalPath)) {
	    		$splite = strrpos($filePath, ".");
	    		$path = substr($filePath, 0, $splite);
	    		$suffix = substr($filePath, $splite);
	    		$finalPath = "{$path}({$index}){$suffix}";
	    		$index++;
	    	}
	    	$res = move_uploaded_file($_FILES['file']['tmp_name'], $finalPath);
	    	
	    	if ($res == false) {
	    		$msg = "移动文件失败，".json_encode(array("source" => $_FILES['file']['tmp_name'], "target" => $finalPath));
	    		throw new Exception($msg, 1);
	    	}
	    	return json_encode(array("state" => 0));
    	} catch (Exception $e) {
    		return json_encode(array("state" => $e->getCode(), "msg" => $e->getMessage()));
    	}
    }

    public function downloadAction() {
    	$name = \Input::get("name");
    	$uploadDir = App::getAppRoot() . "/upload";
    	$filePath = $uploadDir . "/" . $name;

        $fh = fopen($filePath, "r"); // 打开文件
        // 输入文件标签
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".filesize($filePath));
        Header("Content-Disposition: attachment; filename={$name}");
        // 输出文件内容
        echo fread($fh, filesize($filePath));
        fclose($fh);
    }

    private function getFileList() {
		$uploadDir = App::getAppRoot() . "/upload";
		if (!is_dir($uploadDir)) {
    		mkdir($uploadDir);
    	}
    	$fileList = array();
    	$fileNames = scandir($uploadDir);
    	foreach ($fileNames as $name) {
    		$filePath = "{$uploadDir}/{$name}";
    		if (is_file($filePath)) {
    			$time = filectime($filePath);
    			$size = filesize($filePath);
    			$fileList[] = array(
    					"name" => $name,
    					"time" => date("Y-m-d H:i:s", $time),
    					"size" => "{$size} byte",
    				);
    		}
    	}
    	return $fileList;
    }
}
