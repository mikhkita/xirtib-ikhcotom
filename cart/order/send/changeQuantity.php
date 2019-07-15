<?
	if(isset($_REQUEST["id"]) && isset($_REQUEST["quantity"])){
		$res = array(
			"id" => (int)$_REQUEST["id"],
			"quantity" => (int)$_REQUEST["quantity"]
		);
		echo json_encode($res);
	}else{
		echo false;
	}
?>