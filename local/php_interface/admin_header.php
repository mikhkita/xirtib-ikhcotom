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
        },100);

        // $(".adm-info-message-wrap").each(function(){
        //     if( !$(this).find("a:contains(SiteUpdate)").length ){
        //         $(this).css("display", "block");
        //     }
        // });
    })
</script>
<style>
    /*.adm-info-message-wrap{
        display: none;
    }*/
    .no-active td{
        background-color: #e7e9ea;
    }
    .no-active:hover td{
        background-color: #dae0e2 !important;
    }
</style>