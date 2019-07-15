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
      				vardump($strError);
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

	    	if( empty($itemID) ){
	    		die("param ID not found");
	    	}
	    	               
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
	    			returnSuccess($arResult);
	    		}else{
					returnError("Ошибка удаления товара из избранного");
	    		}
	    	}      
	   	} else {
	   		
	   	}
		break;

	case 'FAVOURITE_ADD':
		if( intval($_REQUEST['ID']) > 0 ){
	    	$itemID = intval($_REQUEST['ID']);

	    	if( empty($itemID) ){
	    		die("param ID not found");
	    	}
	    	               
	    	if( $USER->IsAuthorized() ){
	    		$idUser = $USER->GetID();
	    		$rsUser = CUser::GetByID($idUser);
	    		$arUser = $rsUser->Fetch();
	    		$arElements = unserialize($arUser['UF_FAVOURITE']);
	    		$arElements[ $itemID ] = "Y";
	    		$arResult = array(
	    			'ID' => $itemID
	    		);

	    		if( $USER->Update($idUser, Array("UF_FAVOURITE" => serialize($arElements))) ){
	    			returnSuccess($arResult);
	    		}else{
					returnError("Ошибка удаления товара из избранного");
	    		}
	    	}      
	   	} else {
	   		
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

			$PROP = array();

			$PROP["PHONE"]['VALUE'] = $_POST["phone"];

			$userID = $USER->GetID()?$USER->GetID():"";
			if (!empty($userID)) {
				$rsUser = CUser::GetByID($userID);
    			$arUser = $rsUser->Fetch();
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
			  "NAME"              => $_POST["name"],
			  "CODE"		      => $userID,
			  "ACTIVE"            => "N",
			  "PREVIEW_TEXT"      => $_POST['comment'],
			  "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
			  "PREVIEW_PICTURE"   => CFile::MakeFileArray($arUser["PERSONAL_PHOTO"])
			);

			if (isAuth()) {
				$arLoadProductArray['MODIFIED_BY'] = $USER->GetID();
				$arLoadProductArray['NAME'] = $USER->GetFullName();
				if (isset($_POST["phone"])) {
					$arLoadProductArray['NAME'] = $arUser['PERSONAL_PHONE'];
				}
			}
			
			if ($id = $el->Add($arLoadProductArray)) {
				echo "1";
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
				echo "1";
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

	case 'PHONE':

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

			if(CEvent::Send("NEW_PHONE", "s1", array('NAME' => $name, 'PHONE' => $phone))){
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
				$link = "https://nevkusno.ru/ajax/?action=CONFIRM_USER&email=".$email."&hash=".$hash;

				$arFields = Array(
				  // "NAME"              => "Пользователь",
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
	default:
		break;
}
die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>