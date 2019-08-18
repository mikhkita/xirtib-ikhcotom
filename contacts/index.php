<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
		<div class="b-contacts clearfix">
			<div class="b-contacts-left">
				<ul class="b-contacts-list">
					<li class="contacts-map">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-map.svg">
						<p><?includeArea("b-contacts-text-1")?></p>
					</li>
					<li class="contacts-phone">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/call-icon.svg">
						<div class="telephone telephone-1">
							<?includeArea("b-contacts-text-2")?>
							<img src="<?=SITE_TEMPLATE_PATH?>/i/contacts-call-1.svg"></div><br>
						<div class="telephone telephone-2">
							<?includeArea("b-contacts-text-3")?>
							<img src="<?=SITE_TEMPLATE_PATH?>/i/contacts-call-2.svg"></div>
					</li>
					<li class="contacts-email">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/email-icon.svg">
						<?includeArea("b-contacts-text-4")?>
					</li>
					<li class="contacts-vk">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-vk.svg">
						<?includeArea("b-contacts-text-5")?>
					</li>
					<li class="contacts-insta">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-insta.svg">
						<?includeArea("b-contacts-text-6")?>
					</li>
				</ul>
			</div>
			<div class="b-contacts-right">
				<div class="b-contacts-desc b-text">
					<?includeArea("b-contacts-text-7")?>
				</div>
			</div>
		</div>
	</div>
	<div id="map_canvas"></div>
<div class="b-block">
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>