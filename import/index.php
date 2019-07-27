<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$file_path = "goods.csv";

$row = 1;
$keys = array();
$goods = array();
if (($handle = fopen($file_path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 4096*4096, ",")) !== FALSE) {
        $data = (array) $data;


        if( $row == 1 ){
        	$keys = $data;
        }else{
        	var_dump(count($data));
        
        	$data = array_combine($keys, $data);
        	var_dump($data);
        	die();
        	array_push($goods, $data);
        	// $productID = $data["ARTIKUL"];
        	// $weight = $data["VES"];
        	// $quantity = $data["OSTATOK"];
        	// $amount = $data["OSTATOK1"];

        	// updateStore($productID, $weight, $quantity, $amount);

        	// file_put_contents($_SERVER["DOCUMENT_ROOT"]."/1C_exchange/store_log.txt", "$row: ".$data["ARTIKUL"]."\n", FILE_APPEND);
        }

        $row++;
    }
    fclose($handle);
}

var_dump($goods);
var_dump(array_shift($goods));