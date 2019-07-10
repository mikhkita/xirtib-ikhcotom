<?
	if(isset($_REQUEST["coupon"])){
		
		$orders["coupon"] = array();

		$orders["items"][] = array(
			"id" => 25523,
			"image" => "i/cabinet-item.jpg",
			"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
			"url" => "detail.html",
			"quantity" => 10,
			"basePriceForOne" => 280,
			"totalPriceForOne" => 250,
			"maxCount" => 12,
		);
		$orders["items"][] = array(
			"id" => 3451,
			"image" => "i/cabinet-item.jpg",
			"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
			"url" => "detail.html",
			"quantity" => 5,
			"basePriceForOne" => 100,
			"totalPriceForOne" => 100,
			"maxCount" => 100,
		);
		$orders["items"][] = array(
			"id" => 26234,
			"image" => "i/cabinet-item.jpg",
			"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
			"url" => "detail.html",
			"quantity" => 30,
			"basePriceForOne" => 60,
			"totalPriceForOne" => 60,
			"maxCount" => 400,
		);

		echo json_encode($orders);
	}else{
		echo false;
	}
?>