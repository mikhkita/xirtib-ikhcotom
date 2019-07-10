<?
	$orders = array();

	$orders["coupons"][] = array(
		"id" => 723,
		"name" => "zima10",
		"success" => true,
	);
	$orders["coupons"][] = array(
		"id" => 724,
		"name" => "wefe",
		"success" => false,
	);
	$orders["coupons"][] = array(
		"id" => 725,
		"name" => "YTCCTRCT",
		"success" => false,
	);


	$orders["items"][] = array(
		"id" => 25523,
		"image" => "i/cabinet-item.jpg",
		"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
		"url" => "detail.html",
		"quantity" => 10,
		"basePriceForOne" => 250,
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
		"totalPriceForOne" => 80,
		"maxCount" => 100,
	);
	$orders["items"][] = array(
		"id" => 26234,
		"image" => "i/cabinet-item.jpg",
		"name" => "Пряжа Rowan Finest, меринос/альпака/кашемир, 87 м/25 г",
		"url" => "detail.html",
		"quantity" => 30,
		"basePriceForOne" => 60,
		"totalPriceForOne" => 50,
		"maxCount" => 400,
	);

	$orders["delivery"][] = array(
		"id" => 512,
		"name" => "Почта России",
		"value"=>"post",
        "cost"=> 350,
        "text"=> "1. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 513,
		"name"=> "СДЭК",
		"value"=> "SDEC",
        "cost"=> 550,
        "text"=> "2. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 514,
		"name"=> "Курьер по Томску",
		"value"=> "courier",
        "cost"=> 700,
        "text"=> "3. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 515,
		"name"=> "Самовывоз из офиса",
		"value"=>"pickup",
        "cost"=> 0,
        "text"=>"4. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);

	$orders["payments"][] = array(
		"id" => 4234,
		"name"=> "Онлайн-оплата картой",
		"value"=> "online",
	);
	$orders["payments"][] = array(
		"id" => 4235,
		"name"=>"Сбербанк.Онлайн",
		"value"=> "sber",
	);

	echo json_encode($orders);

?>