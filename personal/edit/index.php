<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактирование профиля");?>
<? 

if (isAuth()): 
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	if ($arUser['PERSONAL_PHOTO']){
		$photo = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_EXACT, false, $arFilters );
	}
	$fullName = trim($arUser['NAME']);
?>

<div class="b-cabinet">
	<div class="b-cabinet-profile">
		<div class="b-profile-photo">
			<div class="current-photo" id="pickfiles" style="background-image: url(<?=$photo['src']?>);">
				<div class="background-photo">
					<div class="photo-update-icon"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="b-cabinet-content">
		<form action="/personal/?action=updateUser" method="POST" id="editForm" data-file-action="/addFile.php">
			<div class="b-inputs-3 clearfix">
				<div class="b-input">
					<p>Ф.И.О.</p>
					<input type="text" name="user[NAME]" placeholder="Фамилия Имя Отчество" value="<?=$fullName?>">
				</div>
				<div class="b-input">
					<p>Номер телефона</p>
					<input type="text" name="user[PERSONAL_PHONE]" placeholder="+7 (999) 999 0000" value="<?=convertPhoneNumber($arUser['PERSONAL_PHONE'])?>">
				</div>
				<div class="b-input">
					<p>Электронная почта</p>
					<input type="text" name="user[EMAIL]" placeholder="example@yandex.ru" value="<?=$arUser['EMAIL']?>">
				</div>
			</div>
			<a href="#" class="b-btn b-btn-save ajax">Сохранить изменения</a>
			<a href="#b-popup-save-success" class="b-thanks-link fancy" style="display:none;"></a>
			<a href="#b-popup-error-reg" class="b-error-link fancy" style="display:none;"></a>
		</form>
	</div>
</div>
<? else: ?>
	<?LocalRedirect("/personal/");?>
<? endif; ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>