<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?ShowMessage($arParams["~AUTH_RESULT"]);?>
<div class="bx-auth">
	<div class="popup-sign" id="popup-sign" style="display: inline-block;">
		<div class="popup-sign-form active" id="popup-form-sign-in">
			<form action="<?=$arResult["AUTH_FORM"]?>" method="POST" novalidate="novalidate">
				<div class="b-popup-form">
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
					<input type="hidden" name="AUTH_FORM" value="Y">
					<input type="hidden" name="TYPE" value="CHANGE_PWD">
					<input type="hidden" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />
					<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input"/>

					<input class="bx-auth-input" type="password" id="USER_PASSWORD" name="USER_PASSWORD" placeholder="Пароль" autocomplete="off" required/>
					<input class="bx-auth-input" type="password" id="USER_CONFIRM_PASSWORD" name="USER_CONFIRM_PASSWORD" placeholder="Подтверждение пароля" autocomplete="off" required/>

					<input type="submit" name="change_pwd" style="display:none;">

					<div class="clearfix">
						<a href="/personal/" class="popup-sign-pass-a underline">Авторизация</a>
					</div>
					<a href="#" class="b-btn not-ajax">Изменить</a>
				</div>
			</form>
		</div>
	</div>

	<!-- <form action="" id="pwd_form" class="b-popup-form" method="POST">
        <input type="hidden" name="hash" value="<?=$_REQUEST["hash"]?>">
        <input type="hidden" name="user_id" value="<?=$_REQUEST["user_id"]?>">
        <input type="text" name="new_pass" placeholder="Новый пароль" required="">     
        <div>
        	<button class="b-btn b-btn-sign-in" id="pwd_btn" type="submit">Сохранить</button>
    	</div>
    </form>	 -->
	<!-- <form action="<?=$arResult["AUTH_FORM"]?>" name="bform" method="POST" class="b-confirm-form">
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="CHANGE_PWD">
		<input type="hidden" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />
		<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" />
		<div class="b-popup-error">
		</div>
		<div class="b-popup-form">
			<div class="b-input-container">
				<div class="b-input-string">
					<input class="bx-auth-input" type="password" id="USER_PASSWORD" name="USER_PASSWORD" placeholder="Пароль" autocomplete="off" required/>
				</div>
				<div class="b-input-string">
					<input class="bx-auth-input" type="password" id="USER_CONFIRM_PASSWORD" name="USER_CONFIRM_PASSWORD" placeholder="Подтверждение пароля" autocomplete="off" required/>
				</div>
			</div>
			<input type="text" name="MAIL"/>
			<input type="submit" name="change_pwd" style="display:none;">
			<div class="b-btn-container">
				<a href="#" class="b-btn not-ajax">Изменить</a>
			</div>
		</div>
		<a href="#b-popup-success-reg" class="b-thanks-link fancy" style="display:none;"></a>
	</form> -->

	<!-- <p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
	<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>
	<p>
		<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a>
	</p> -->

<!-- </form> -->

<script type="text/javascript">
	document.bform.USER_LOGIN.focus();
</script>
</div>