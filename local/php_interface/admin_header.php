<? CJSCore::Init( 'jquery' ); ?>
<script>

	$(document).ready(function(){	
		function highlight(){
			var index = 0;
			$('tr.main-grid-row-head').find('th').each(function(i){
				var text = $(this).find('.main-grid-head-title').text();
				if (text == "Активность") {
					index = i;
					return false;
				}
			});

			$('.main-grid-table').find('.main-grid-row').each(function(){
				if ($(this).find('td').eq(index).find('span').text() == "Нет") {
					$(this).addClass('no-active');
				}
			})
		}

		highlight();

		setInterval(function(){
			highlight();
		},1000)
	})
</script>
<style>
	.no-active td{
		background-color: #e6e6e6;
	}
</style>