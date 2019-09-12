<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();

$action = (isset($_GET["action"]))?$_GET["action"]:NULL;
$action = (isset($_GET["actions"]))?$_GET["actions"]:$action;
$isBasket = isset($_GET["basket"]);
$hashKey = 481516;

switch ($action) {
	case 'BUY':
	case 'ADD2BASKET':
		$productId = $_GET["element_id"];
		$quantity = (isset($_GET["quantity"]))?$_GET["quantity"]:1;

		if (CModule::IncludeModule("catalog")){
		    if (($action == "ADD2BASKET" || $action == "BUY")){
		    	Add2BasketByProductID($productId,$quantity);
				if($ex = $APPLICATION->GetException()){
      				$strError = $ex->GetString();
      				//vardump($strError);
      				returnError("Ошибка! ".$strError);
      			}
		    }
		}
			
		$result = getBasketCount();

		if( isset($_GET["gift"]) ){
			$result["action"] = "reload";
		}

		returnSuccess( $result );
		break;

	case 'FAVOURITE_REMOVE':
		if( intval($_REQUEST['ID']) > 0 ){
	    	$itemID = intval($_REQUEST['ID']);
	    	               
	    	if( $USER->IsAuthorized() ){
	    		$idUser = $USER->GetID();
	    		$rsUser = CUser::GetByID($idUser);
	    		$arUser = $rsUser->Fetch();
	    		$arElements = unserialize($arUser['UF_FAVOURITE']);
	    		$arElements[ $itemID ] = "N";
	    		$arResult = array(
	    			'ID' => $itemID
	    		);

	    		if( $USER->Update($idUser, Array("UF_FAVOURITE" => serialize($arElements))) ){
	    			$arFavourites = getFavourites();
		    		$arResult['COUNT'] = count($arFavourites);
	    			returnSuccess($arResult);
	    		}else{
					returnError("Ошибка удаления товара из избранного");
	    		}
	    	}      
	   	} else {
	   		returnError("Ошибка добавления в избранное: не передан ID товара");
	   	}
		break;

	case 'FAVOURITE_ADD':
		if( intval($_REQUEST['ID']) > 0 ){
	    	$itemID = intval($_REQUEST['ID']);
	    	               
	    	if( $USER->IsAuthorized() ){
	    		$idUser = $USER->GetID();
	    		$rsUser = CUser::GetByID($idUser);
	    		$arUser = $rsUser->Fetch();
	    		$arElements = unserialize($arUser['UF_FAVOURITE']);
	    		$arElements[ $itemID ] = "Y";
	    		$arResult = array(
	    			'ID' => $itemID
	    		);

	    		if( $USER->Update($idUser, array("UF_FAVOURITE" => serialize($arElements))) ){
	    			$arFavourites = getFavourites();
		    		$arResult['COUNT'] = count($arFavourites);
	    			returnSuccess($arResult);
	    		}else{
					returnError("Ошибка добавления товара в избранное");
	    		}
	    	}      
	   	} else {
	   		returnError("Ошибка добавления в избранное: не передан ID товара");
	   	}
		break;

	case 'REMOVE':
		if( !isset($_GET["ELEMENT_ID"]) ){
			returnError("Не указан ID товара");
		}
		$productId = $_GET["ELEMENT_ID"];

		//Получение корзины текущего пользователя
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(), 
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);

		// Получение товара корзины по ID товара
		if( !$basket->getItemById($productId)->delete() ){
			returnError("Не найден товар с ID равным ".$productId);
		}	

		//Сохранение изменений
		if( $basket->save() ){
			$result = "error";
			returnSuccess(array(
				"sum" => number_format( getBasketSum(), 0, ',', ' ' )
			));
		}else{
			returnError("Ошибка сохранения товара");
		}
		break;

	case 'QUANTITY':
		// sleep(rand(1, 3));

		if( !isset($_GET["ELEMENT_ID"]) ){
			returnError("Не указан ID товара");
		}
		if( !isset($_GET["QUANTITY"]) ){
			returnError("Неверно передно количество");
		}
		$productId = $_GET["ELEMENT_ID"];
		$quantity = $_GET["QUANTITY"];

		//Получение корзины текущего пользователя
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(), 
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);

		foreach ($basket as $basketItem) {
		    if( $basketItem->getProductId() == $productId ){
		    	if( intval($quantity) == 0 ){
		    		if( $basketItem->delete() && $basket->save() ){
		    			$basketInfo = getBasketCount();
		    			returnSuccess(array(
							"id" => $productId,
							"quantity" => 0,
							"count" => $basketInfo["count"],
							"sum" => $basketInfo["sum"],
						));
		    		}else{
		    			returnError("Ошибка удаления товара из корзины");
		    		}
		    	}else{
		    		$basketItem->setField("QUANTITY", $quantity);

			    	// Сохранение изменений
			    	if( $basketItem->save() ){
			    		$basketInfo = getBasketCount();
			    		returnSuccess(array(
							"id" => $productId,
							"quantity" => intval($basketItem->getField("QUANTITY")),
							"count" => $basketInfo["count"],
							"sum" => $basketInfo["sum"],
						));
			    	}else{
			    		returnError("Не удалось сохранить товар");
			    	}
		    	}
		    }
		}

		if (CModule::IncludeModule("catalog")){
	        Add2BasketByProductID(
                $productId,
                $quantity
            );

	        $basketInfo = getBasketCount();
            returnSuccess(array(
				"id" => $productId,
				"quantity" => $quantity,
				"count" => $basketInfo["count"],
				"sum" => $basketInfo["sum"],
			));
		}

		returnError("Не найден товар с ID равным ".$productId);

		break;
	case 'ADDREVIEW':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['comment'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {
			CModule::IncludeModule("iblock");
			$el = new CIBlockElement;

			$userID = $USER->GetID()?$USER->GetID():"";
			$PROP = array();

			if (!empty($userID)) {
				$rsUser = CUser::GetByID($userID);
    			$arUser = $rsUser->Fetch();

    			$PROP["PHONE"]['VALUE'] = isset($_POST["phone"]) ? $_POST["phone"] : $arUser['PERSONAL_PHONE'];
    			$name = isset($_POST["name"]) ? $_POST["name"] : $arUser['NAME'];

			}

			$productID = (isset($_GET["PRODUCT_ID"])) ? $_GET["PRODUCT_ID"] : NULL;
			$sectionID = 7;
			
			if ($productID) {
				$sectionID = 8;
				$PROP["PRODUCT_ID"]['VALUE'] = $productID;
			}

			$arLoadProductArray = Array(
			  "IBLOCK_ID"         => 3,
			  "IBLOCK_SECTION_ID" => $sectionID,
			  "PROPERTY_VALUES"   => $PROP,
			  "NAME"              => $name,
			  "CODE"		      => $userID,
			  "ACTIVE"            => "N",
			  "PREVIEW_TEXT"      => $_POST['comment'],
			  "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
			  "PREVIEW_PICTURE"   => CFile::MakeFileArray($arUser["PERSONAL_PHOTO"])
			);

			if ($id = $el->Add($arLoadProductArray)) {
				$link = 'http://motochki-klubochki.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=3&type=content&ID='.$id.'&lang=ru&find_section_section='.$sectionID.'&WF=Y';
				if(CEvent::Send("NEW_REVIEW", "s1", array('NAME' => $name, 'PHONE' => $phone, 'LINK' => $link))){
					echo "1";
				} else {
					echo "0";
				}
			} else {
				echo "0";
			}
		}else{
			echo "0";
		}

		break;

	case 'ADDCOMMENT':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['comment'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {
			CModule::IncludeModule("iblock");
			$el = new CIBlockElement;

			$userID = $USER->GetID()?$USER->GetID():"";
			if (!empty($userID)) {
				$rsUser = CUser::GetByID($userID);
    			$arUser = $rsUser->Fetch();
			}

			$arLoadProductArray = Array(
			  "IBLOCK_ID"         => 5,
			  'MODIFIED_BY'       => $userID,
			  "NAME"              => $USER->GetFullName(),
			  "CODE"		      => $_GET["ARTICLE_ID"],
			  "ACTIVE"            => "N",
			  "PREVIEW_TEXT"      => $_POST['comment'],
			  "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
			  "PREVIEW_PICTURE"   => CFile::MakeFileArray($arUser["PERSONAL_PHOTO"])
			);
			
			if ($id = $el->Add($arLoadProductArray)) {
				$link = 'http://motochki-klubochki.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=5&type=content&ID='.$id.'&lang=ru&find_section_section=0&WF=Y';
				if(CEvent::Send("NEW_COMMENT", "s1", array('NAME' => $USER->GetFullName(), 'ARTICLE' => $_POST['article'], 'LINK' => $link))){
					echo "1";
				} else {
					echo "0";
				}
			} else {
				echo "0";
			}
		}else{
			echo "0";
		}

		break;

	case 'WHATSAPP':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['name']) || empty($_POST['phone'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {

			$name = $_POST['name'];
			$phone = $_POST['phone'];

			if(CEvent::Send("WHATSAPP", "s1", array('NAME' => $name, 'PHONE' => $phone,))){
				echo "1";
			} else {
				echo "0";
			}
		}else{
			echo "1";
		}

		break;

	case 'SUBSCRIBE':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['name']) || empty($_POST['email'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {

			$name = $_POST['name'];
			$email = $_POST['email'];

			if(CEvent::Send("NEW_SUBSCRIBE", "s1", array('NAME' => $name, 'EMAIL' => $email,))){
				echo "1";
			} else {
				echo "0";
			}
		}else{
			echo "1";
		}

		break;

	case 'CALLBACK':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['comment'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {

			$name = $_POST['name'];
			$phone = $_POST['phone'];
			$comment = $_POST['comment'];

			if(CEvent::Send("CALLBACK", "s1", array('NAME' => $name, 'PHONE' => $phone, 'COMMENT' => $comment))){
				echo "1";
			} else {
				echo "0";
			}
		}else{
			echo "1";
		}

		break;
		
	case 'ASK':

		if (empty($_POST["MAIL"])){
			if (empty($_POST['name']) || empty($_POST['phone'])) {
				$spam = true;
			}
			else {
				$spam = false;
			}
		}else{
			$spam = true;
		}

		if (!$spam) {

			$name = $_POST['name'];
			$phone = $_POST['phone'];
			$comment = $_POST['comment'];

			if(CEvent::Send("NEW_ASK", "s1", array('NAME' => $name, 'PHONE' => $phone, 'COMMENT' => $comment,))){
				echo "1";
			} else {
				echo "0";
			}
		}else{
			echo "1";
		}

		break;

	case 'REG':

		if (empty($_POST["MAIL"])){
			$spam = false;
		} else {
			$spam = true;
		}

		if (!$spam) {

			$filter = Array("EMAIL" => $_POST['email']);
			$rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
			$arUser = $rsUser->Fetch();

			if(!$arUser){

				$email = $_POST['email'];
				$password = $_POST['password'];
				$user = new CUser;
				$hash = md5($email.$hashKey);
				$link = "http://motochki-klubochki.ru/ajax/?action=CONFIRM_USER&email=".$email."&hash=".$hash;

				$arFields = Array(
				  "EMAIL"             => $email,
				  "LOGIN"             => $email,
				  "LID"               => "ru",
				  "ACTIVE"            => "N",
				  "PASSWORD"          => $password,
				  "CONFIRM_PASSWORD"  => $password,
				);

				if ($user->Add($arFields)){
				    if(CEvent::Send("NEW_USER_CONFIRM", "s1", array('EMAIL' => $email, "LINK" => $link))){
						echo "1";
					} else {
						returnError("Ошибка регистрации.");
					}
				}
				else{
				    echo "0";
				}
			} else {
				returnError("Пользователь с таким E-mail уже зарегистрирован.");
			}
		}else{
			echo "0";
		}

		break;
	case 'CONFIRM_USER':

		$email = $_GET['email'];
		$userHash = $_GET['hash'];
		$hash = md5($email.$hashKey);

		if ($userHash == $hash) {

			$filter = Array("EMAIL" => $email);
			$rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
			$arUser = $rsUser->Fetch();

			$user = new CUser;
			$fields = Array(
			  "ACTIVE" => "Y",
			);
			
			if ($user->Update($arUser["ID"], $fields)) {
				$USER->Authorize($arUser["ID"]);
			 	LocalRedirect("/personal/");
			} 
			else {
				LocalRedirect("/");
			}

		}	

		break;
	case 'ADD2RESERVE':
		$userID = $USER->GetID()?$USER->GetID():0;

		if ($userID != 0) {

			$arFilter = Array(
				"IBLOCK_ID" => 9,
				"ACTIVE_DATE" => "Y",
				"ACTIVE" => "Y",
				"CODE" => $_REQUEST["id"],
				"PROPERTY_USER_VALUE" => $userID,
			);

			$res = CIBlockElement::GetList(array(), $arFilter, array(), false, array());

			if (isset($res) && $res == 0) {

				$el = new CIBlockElement;

				$PROP["USER"]['VALUE'] = $userID;
				$name = str_replace("_", " ", $_REQUEST["name"]);

				$arLoadProductArray = Array(
				  "IBLOCK_ID"      => 9,
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => $name,
				  "CODE"           => $_REQUEST["id"],
				);

				if($PRODUCT_ID = $el->Add($arLoadProductArray))
				  echo "success";
				else
				  echo "error";
			} else {
				echo "already-reserved";
			}
		}
		break;
	case 'COUPON_ACTION':
		if( !isset($_REQUEST['COUPON_NAME']) ){
			returnError("Не указан купон");
		}
		$coupon = $_REQUEST['COUPON_NAME'];
		
		$arFavourites = getFavourites();
		$res["isAuth"] = isAuth();

		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(),
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);

		$order = Bitrix\Sale\Order::create(
			\Bitrix\Main\Context::getCurrent()->getSite(),
			\Bitrix\Sale\Fuser::getId());
		$order->setPersonTypeId(1);
		$order->setBasket($basket);

		if(isset($_REQUEST['COUPON_DELETE']) && $_REQUEST['COUPON_DELETE'] == "Y"){
			Bitrix\Sale\DiscountCouponsManager::delete($coupon);
		}else{
			Bitrix\Sale\DiscountCouponsManager::add($coupon);
		}
		$discounts = $order->getDiscount();
		$discounts->calculate();
		$basket->refresh();

		$arCoupons = Bitrix\Sale\DiscountCouponsManager::get(true, array(), true, true);
		$res["coupons"] = array();
		$i = 0;
		foreach ($arCoupons as $value) {
			if ($value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_NOT_FOUND 
				|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_FREEZE
				|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_NOT_APPLYED 
				|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_ENTERED){
				$status = false;
			}
			else{
				$status = true;
			}
			$res["coupons"][] = array(
				"id" => $i,
				"name" => $value["COUPON"],
				"success" => $status,
				"visible" => true
			);
			$i++;
		}

		$arBasket = array();
		$basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
		foreach ($basketItems as $basketItem) {
			$arBasketItem = array();

			$arBasketItem["id"] = $basketItem->getProductId();//торговое предложение
			$productID = CCatalogSku::GetProductInfo($arBasketItem["id"]);//получить id товара по id торгового предложения
			if(is_array($productID)){
				$arBasketItem["productID"] = $productID["ID"];
				$db_res = CIBlockElement::GetList(
			        array(),
			        array("ID" => $productID["ID"]),
			        false,
			        array("nTopCount" => 10)
			    );
				if ($ar_res = $db_res->Fetch()){
					$arBasketItem["productImage"] = $ar_res["DETAIL_PICTURE"];
				    $arBasketItem["productName"] = $ar_res["NAME"];
				}
			}else{
				$arBasketItem["productID"] = $arBasketItem["id"];
			}

			$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
			if(empty($objElement)){
				$basketItem->delete();
				$basket->save();
				continue;
			}
			if($objElement->getDetailPicture()){
				$img = CFile::ResizeImageGet($objElement->getDetailPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
			}else if($objElement->getPreviewPicture()){
				$img = CFile::ResizeImageGet($objElement->getPreviewPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
			}else if($arBasketItem["productImage"]){
				$img = CFile::ResizeImageGet($arBasketItem["productImage"], array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
			}else{
				$img["src"] = SITE_TEMPLATE_PATH.'/i/hank.svg';
			}
			$arBasketItem["image"] = $img["src"];
			$arBasketItem["name"] = $basketItem->getField('NAME');
			$arBasketItem["url"] = $basketItem->getField('DETAIL_PAGE_URL');
			$arBasketItem["quantity"] = $basketItem->getQuantity();
			$arBasketItem["basePriceForOne"] = $basketItem->getBasePrice();
			$arBasketItem["totalPriceForOne"] = $basketItem->getPrice();
			$product = \Bitrix\Catalog\ProductTable::getByPrimary($arBasketItem["id"])->fetchObject();
			$arBasketItem["maxCount"] = $product->getQuantity();
			$arBasketItem["favorite"] = in_array($arBasketItem["productID"], $arFavourites);
			$arBasketItem["visible"] = true;
		    $arBasket[] = $arBasketItem;
		}
		$res["items"] = $arBasket;
		returnSuccess($res);
		break;
	case 'DELIVERY':
		$deliveryId = $_REQUEST["delivery_id"];
		$zip = $_REQUEST["zip"];

		if( empty($deliveryId) ){
			returnError("Не передан ID доставки");
		}

		if( empty($zip) ){
			returnError("Не передан индекс");
		}

		// Получаем текущую корзину пользователя
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(),
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);

		// Создаем заказ и добавляем в него корзину
		$order = Bitrix\Sale\Order::create(
			\Bitrix\Main\Context::getCurrent()->getSite(),
			\Bitrix\Sale\Fuser::getId());
		$order->setPersonTypeId(1);
		$order->setBasket($basket);

		// Получаем коллекцию отгрузок и добавляем в нее новую отгрузку
		$shipmentCollection = $order->getShipmentCollection(); 
		$shipment = $shipmentCollection->createItem();
		$shipment->setFields(array(
			'CURRENCY' => $order->getCurrency()
		));

		// Добавляем к отгрузке всю корзину
		$shipmentItemCollection = $shipment->getShipmentItemCollection(); 
		foreach ($order->getBasket() as $item) {
			$shipmentItem = $shipmentItemCollection->createItem($item);
			$shipmentItem->setQuantity($item->getQuantity());
		}

		// Получаем ID местоположения по ZIP коду
		$locationId = getLocationByZIP($zip);

		// Если ID нашли, то записываем его в свойство типа «Местоположение» к заказу
		if( $locationId ){
			$propertyCollection = $order->getPropertyCollection();//получаем коллекцию свойств заказа
			$property = $propertyCollection->getDeliveryLocation();//выбираем ту что отвечает за местоположение
			$property->setValue($locationId);//передаем местоположение
		}

		// Получаем службу доставки
		$deliveryObj = \Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId);

		// Какой-то костыль
		$shipment->setField('CUSTOM_PRICE_DELIVERY', 'N');

		// Рассчитываем стоимость доставки для отгрузки
		$shipment->setField('DELIVERY_ID', $deliveryObj->getId());
		$order->getShipmentCollection()->calculateDelivery();
		$calcResult = $deliveryObj->calculate($shipment);
		if ($calcResult->isSuccess()) {
			$price = \Bitrix\Sale\PriceMaths::roundByFormatCurrency($calcResult->getPrice(), $order->getCurrency());
			returnSuccess(array(
				"cost" => $price
			));
		}else{
			// var_dump($calcResult->getErrors());
			// die();
			returnError("Не удалось рассчитать стоимость доставки. Наш оператор свяжется с вами после оформления заказа и поможет рассчитать стоимость доставки.");
			// returnError(print_r($calcResult->getErrors(), true));
		}
		break;
	case 'COMPOSIT':
		$arResult = array();
		$arResult["isAuth"] = isAuth();
		$arResult["arFav"] = getFavourites();
		$arUser = getUserFields();
		if ($arUser){
			$arResult["userName"] = $arUser["NAME"];
		}
		returnSuccess($arResult);
		break;
	default:
		break;
}
die();

function returnError( $text ){
	echo json_encode(array(
		"result" => "error",
		"error" => $text
	));
	die();
}

function returnSuccess( $array ){
	$arResult = array(
		"result" => "success"
	);
	$arResult = $arResult + $array;

	echo json_encode($arResult);
	die();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>