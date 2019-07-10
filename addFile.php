<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
	CModule::IncludeModule('iblock');
	$el = new CIBlockElement;
	global $USER;
	$userID = $USER->GetID();
	@set_time_limit(2 * 60);

	$tmpDir = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/";
	//$tmpDir = 'uploads';
	$cleanuptmpDir = true;
	$maxFileAge = 5 * 3600;


	if (!empty($_FILES['file'])){

		// Create target dir
		if (!file_exists($tmpDir)) {
			@mkdir($tmpDir);
		}

		$whitelist = array("jpeg", "jpg", "png", "bmp", "pdf", "doc", "docx", "zip", "xls", "xlsx");
		$errorFormat = true;
		$result = array();

		//проверка формата файла
		foreach ($whitelist as $item) {
			if(preg_match("/$item\$/i",$_FILES['file']['name'])) 
				$errorFormat = false;
		}
		if($errorFormat){
			die('{"status" : false, "error" : "Неверный формат"}');
		}

		$format = end(explode(".", $_FILES['file']['name']));
		//сгенерить новое имя для файла
		do {
			$filename = md5(uniqid(""));
	        $fileURL = $tmpDir.$filename.'.'.$format;
	    } while (file_exists($fileURL));

		$filePath = $tmpDir.$filename.'.'.$format;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


		// Remove old temp files	
		if ($cleanuptmpDir) {
			if (!is_dir($tmpDir) || !$dir = opendir($tmpDir)) {
				//die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
				die('{"status" : false, "error" : "Failed to open temp directory."}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $tmpDir . DIRECTORY_SEPARATOR . $file;

				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	


		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"status" : false, "error" : "Failed to open output stream."}');
		}

		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"status" : false, "error" : "Failed to move uploaded file."}');
			}

			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"status" : false, "error" : "Failed to open input stream."}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				die('{"status" : false, "error" : "Failed to open input stream."}');
			}
		}

		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}

		// Return Success JSON-RPC response
		die(json_encode(array("status"=>true,"filePath"=>$filename.'.'.$format)));
		//die('{"jsonrpc" : "2.0","id" : "id"}');
	}else{
		die('{"status" : false, "error" : "Не удалось загрузить файл"}');
	}
?>