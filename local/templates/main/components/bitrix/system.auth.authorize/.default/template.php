<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
ShowMessage($arResult['ERROR_MESSAGE']);
?>
<div class="b-btn-container">
	<div class="b-popup popup-sign" style="display: inline-block;">
		<img src="/local/templates/main/i/popup-logo.svg" alt="" class="popup-img">
		<ul class="popup-sign-list">
			<li><a href="#form-sign-in" class="active">Войти</a></li>
			<li><a href="#form-sign-up">Регистрация</a></li>
		</ul>
		<div class="popup-sign-form active" id="form-sign-in">
			<form action="/personal/?action=authSite&amp;login=yes" method="POST" novalidate="novalidate">
				<div class="b-popup-error"></div>
				<div class="b-popup-form">
					<input type="hidden" name="AUTH_FORM" value="Y">
	                <input type="hidden" name="TYPE" value="AUTH">
	                <input type="hidden" name="Login" value="Войти">

					<input type="text" name="USER_LOGIN" placeholder="Электронная почта" required="">
					<input type="password" name="USER_PASSWORD" placeholder="Пароль" required="">

					<div class="clearfix">
						<a href="/personal/?forgot_password=yes" class="popup-sign-pass-a underline right">Забыли пароль?</a>
					</div>
					<div class="b-btn-container">
						<a href="#" class="b-btn b-btn-sign-in ajax">Войти</a>
					</div>
				</div>
			</form>
		</div>
		<div class="popup-sign-form" id="form-sign-up">
			<form action="/ajax/?action=REG" method="POST" novalidate="novalidate" id="regForm">
				<div class="b-popup-error"></div>
				<div class="b-popup-form">
					<input type="text" name="email" placeholder="Электронная почта" required="">
					<input type="password" name="password" placeholder="Пароль" required="">
					<input type="text" name="MAIL">
					<div class="b-btn-container">
						<a href="#" class="b-btn b-btn-sign-up ajax">Зарегистрироваться</a>
					</div>
					<a href="#b-popup-success-reg" class="b-thanks-link fancy" style="display:none;"></a>
					<a href="#b-popup-error-reg" class="b-error-link fancy" style="display:none;"></a>
				</div>
			</form>
		</div>
	</div>
</div>
