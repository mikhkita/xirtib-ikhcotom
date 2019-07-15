<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	use Bitrix\Main;
	use Bitrix\Sale;
	CModule::IncludeModule("sale");

	$res = false;
	if(isset($_REQUEST["id"])){

		$id = (int)$_REQUEST["id"];
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		   \Bitrix\Sale\Fuser::getId(),
		   \Bitrix\Main\Context::getCurrent()->getSite()
		);
		//Получим товары корзины
		$basketItems = $basket->getBasketItems();
		//Найти товар по id
		foreach ($basketItems as $basketItem) {
			if((int)$basketItem->getProductId() == $id){
				$basketItem->delete();
				$res = true;
				$basketItem->save(); 
			}
		}
		//$basket->save();
	}
	echo $res;
?>