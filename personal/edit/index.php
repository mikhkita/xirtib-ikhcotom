<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактирование профиля");?>
<? 

if (isAuth()): 
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
?>
<h2 class="b-title"><?$APPLICATION->ShowTitle();?></h2>
<div class="b-cabinet">
	<div class="b-cabinet-profile">
		<div class="b-profile-photo">
			<div class="current-photo" id="pickfiles">
				<? if ($arUser['PERSONAL_PHOTO']): ?>
					<? $photo = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters ); ?>
					<img src="<?=$photo['src']?>">
				<? endif; ?>
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
					<input type="text" name="user[NAME]" placeholder="Фамилия Имя Отчество" value="<?=$arUser['LAST_NAME']?> <?=$arUser['NAME']?> <?=$arUser['SECOND_NAME']?>">
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
		</form>
	</div>
</div>
<? else: ?>
	<?LocalRedirect("/personal/");?>
<? endif; ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>