<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	use Bitrix\Main;
	use Bitrix\Sale;
	CModule::IncludeModule("sale");

	if(isset($_REQUEST["id"]) && isset($_REQUEST["quantity"])){

		$id = (int)$_REQUEST["id"];
		$quantity = (int)$_REQUEST["quantity"];
		$res = array();
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(),
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);
		//Получим товары корзины
		$basketItems = $basket->getBasketItems();
		//Найти товар по id
		foreach ($basket as $basketItem) {
			if((int)$basketItem->getProductId() == $id){
				$basketItem->setField('QUANTITY', $quantity);
				
				$basketItem->save();
			
				$res = array(
					"id" => $id,
					"quantity" => $basketItem->getQuantity()
				);
				break;
			}
		}
		//$basket->save();
		echo json_encode($res);
	}else{
		echo false;
	}
?>