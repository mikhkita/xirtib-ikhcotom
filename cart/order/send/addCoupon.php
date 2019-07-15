<?
	$coupons = array("zima10", "leto10");
	if(isset($_REQUEST["coupon"])){
		if(in_array($_REQUEST["coupon"], $coupons)){
			
			$orders = array();

			//получить список купонов из битры (среди них вроде должны быть как применённые, так и не применённые)
			$orders["coupons"] = array();
			$orders["coupons"][] = array(
				"id" => 234,
				"name" => "zima10",
				"success" => true, //применился купон или нет
				"discount" => 5.5, //скидка в процентах
			);
			$orders["coupons"][] = array(
				"id" => 235,
				"name" => "erhuyhe",
				"success" => false,
				"discount" => 0,
			);
			$orders["coupons"][] = array(
				"id" => 236,
				"name" => "RTTRTRT",
				"success" => false,
				"discount" => 0,
			);

			//получить список позиций
			$orders["items"][] = array(
				"id" => 25523,
				"image" => "i/cabinet-item.jpg",
				"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
				"url" => "detail.html",
				"quantity" => 10,
				"basePriceForOne" => 200,
				"totalPriceForOne" => 180,
				"maxCount" => 12,
			);
			$orders["items"][] = array(
				"id" => 3451,
				"image" => "i/cabinet-item.jpg",
				"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
				"url" => "detail.html",
				"quantity" => 5,
				"basePriceForOne" => 100,
				"totalPriceForOne" => 90,
				"maxCount" => 100,
			);
			$orders["items"][] = array(
				"id" => 26234,
				"image" => "i/cabinet-item.jpg",
				"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
				"url" => "detail.html",
				"quantity" => 30,
				"basePriceForOne" => 60,
				"totalPriceForOne" => 54,
				"maxCount" => 400,
			);

			echo json_encode($orders);

		}else{
			echo false;
		}
	}
?>