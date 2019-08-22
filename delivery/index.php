<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доставка и оплата");
?>
	<div class="b-delivery-info">
		<div class="delivery-info-item">
			<div class="delivery-info-item-title"><?includeArea("b-delivery-text-1")?></div>
			<p><?includeArea("b-delivery-text-2")?></p>
			<div class="delivery-info-list-title"><?includeArea("b-delivery-text-3")?></div><br>
			<?includeArea("b-delivery-text-4")?>
		</div>
		<div class="delivery-info-text">
			<?includeArea("b-delivery-text-5")?>
		</div>
	</div>
	<div class="b-payment">
		<h2 class="payment-title"><?includeArea("b-delivery-text-6")?></h2>
		<ul class="payment-list">
			<li>
				<div class="payment-list-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/payment-img-1.svg" alt=""></div>
				<div class="payment-list-text"><?includeArea("b-delivery-text-7")?></div>
			</li>
			<li>
				<div class="payment-list-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/payment-img-2.svg" alt=""></div>
				<div class="payment-list-text"><?includeArea("b-delivery-text-8")?></div>
			</li>
		</ul>
		<div class="payment-info">
			<div class="payment-info-title"><?includeArea("b-delivery-text-9")?></div>
			<div class="payment-info-text">
				<?includeArea("b-about-text-5")?>
			</div>
		</div>
	</div>
	<div class="b-delivery-methods">
		<div class="delivery-methods-title"><?includeArea("b-delivery-text-10")?></div>
		<div class="delivery-methods-list">
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-1.svg" alt=""></div>
				<div class="delivery-method-title"><?includeArea("b-delivery-text-11")?></div>
				<div class="delivery-method-text"><?includeArea("b-delivery-text-12")?></div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-2.svg" alt=""></div>
				<div class="delivery-method-title"><?includeArea("b-delivery-text-13")?></div>
				<div class="delivery-method-text"><?includeArea("b-delivery-text-14")?></div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-3.svg" alt=""></div>
				<div class="delivery-method-title"><?includeArea("b-delivery-text-15")?></div>
				<div class="delivery-method-text"><?includeArea("b-delivery-text-16")?></div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-4.svg" alt=""></div>
				<div class="delivery-method-title"><?includeArea("b-delivery-text-17")?></div>
				<div class="delivery-method-text"><?includeArea("b-delivery-text-18")?></div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>