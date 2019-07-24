<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<div class="b-btn-container">
	<div class="b-popup popup-sign" style="display: inline-block;">
		<img src="/local/templates/main/i/popup-logo.svg" alt="" class="popup-img">
		<p>Если вы забыли пароль, введите ваш E-Mail.<br>На него будет выслана ссылка для смены пароля.</p>
		<div class="popup-sign-form active" id="form-sign-in">
			<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
				<div class="b-popup-form">
					<input type="hidden" name="AUTH_FORM" value="Y">
					<input type="hidden" name="TYPE" value="SEND_PWD">

					<input type="text" name="USER_EMAIL" placeholder="Электронная почта" required=""/>

					<div class="clearfix">
						<a href="/personal/" class="popup-sign-pass-a underline right">Авторизация</a>
					</div>

					<div class="b-btn-container">
						<input type="submit" name="send_account_info" value="Отправить" class="b-btn b-btn-submit"/>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	document.bform.USER_LOGIN.focus();
</script>
