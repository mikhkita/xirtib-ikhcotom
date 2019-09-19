var progress = new KitProgress("#5F9827", 4),
    filterAjax = null;

$(document).ready(function(){	

    var isDesktop = false,
        isTablet = false,
        isMobile = false,
        isMobileSmall = false;
        startOffsetY = window.pageYOffset;
    
    function resize(){
       if( typeof( window.innerWidth ) == 'number' ) {
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }

        isDesktop = isTablet = isMobile = isMobileSmall = false;
        if( myWidth > 1188 ){
            isDesktop = true;
        }else if( myWidth > 767 ){
            isTablet = true;
        }else{
            isMobile = true;
            if(myWidth < 664){
                isMobileSmall = true;
            }
        }

        if(isMobile){
            if($(".b-product-content .b-product-name").length){
                $(".b-product").prepend($(".b-product-actions-wrap"));
                $(".b-product").prepend($(".b-product-name"));
            }
        }else{
            if(!$(".b-product-content .b-product-name").length){
                $(".b-product-content").prepend($(".b-product-actions-wrap"));
                $(".b-product-content").prepend($(".b-product-name"));
            }
        }

        // cardImgHeight();
        cardHeight();

    }

    $(window).resize(resize);
    resize();

    $.fn.placeholder = function() {
        if(typeof document.createElement("input").placeholder == 'undefined') {
            $('[placeholder]').focus(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function() {
                var input = $(this);
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur().parents('form').submit(function() {
                $(this).find('[placeholder]').each(function() {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });
            });
        }
    }
    $.fn.placeholder();

    /******************************************/

    var menuSlideout = new Slideout({
        'panel': document.getElementById('panel-page'),
        'menu': document.getElementById('mobile-menu'),
        'side': 'left',
        'padding': 300,
        'touch': false
    });

    if ($('#b-filter-panel').length && !isDesktop) {
        var filterSlideout = new Slideout({
            'panel': document.getElementById('panel-page'),
            'menu': document.getElementById('b-filter-panel'),
            'side': 'right',
            'padding': 300,
            'touch': false
        });
    }

    $(document).on('init', '.b-product-main', function(slick){
        // console.log('init');
        setTimeout(selectOffer, 1);
    });

    $(document).on('change', '.edit-checkbox', function(){
        if ($('.edit-pass-cont').hasClass('hide')) {
            $('.edit-pass-cont').removeClass('hide');
        } else {
            $('.edit-pass-cont').addClass('hide');
        }
    });



    function selectOffer(){
        // console.log('select');
        var url = window.location.href.split('/');
        if(url[url.length - 1].indexOf('#') == 0){
            var id = url[url.length - 1].split('#');
            if (id[1] !== '') {
                $('.colors-select option[data-color-id='+id[1]+']').prop('selected', true);
                $('.colors-select').change().trigger('chosen:updated');
            }
        }
    }

    $('.mobile-menu').removeClass("hide");
    $('.filter-mobile').removeClass("hide");

    $(document).on('click', '.mobile-btn', function(){
        menuSlideout.open();
        $('.mobile-menu').show();
        $('.filter-mobile').hide();
        $(".b-menu-overlay").show();
        return false;
    });

    $(document).on('click', '.catalog-mobile-filter', function(){
        filterSlideout.open();
        $('.filter-mobile').show();
        $('.mobile-menu').hide();
        $(".b-menu-overlay").show();
        return false;
    });

    $(document).on('click', '.b-menu-overlay', function(){
        menuSlideout.close();
        if ($('#b-filter-panel').length){
            $('.filter-mobile').removeClass('show-btn');
            filterSlideout.close();
        }
        $('.b-menu-overlay').hide();
        return false;
    });

    menuSlideout.on('open', function() {
        $('.mobile-menu').removeClass("hide");
        $(".b-menu-overlay").show();
    });

    menuSlideout.on('close', function() {
        setTimeout(function(){
            $("body").unbind("touchmove");
            $(".filter-mobile, #mobile-menu").hide();
            $(".b-menu-overlay").hide();
        },100);
    });

    if ($('#b-filter-panel').length && !isDesktop){
        filterSlideout.on('open', function() {
            $('.filter-mobile').removeClass("hide");
            $(".b-menu-overlay").show();
        });

        filterSlideout.on('close', function() {
            ajaxFilter($('.filter-mobile'));
            setTimeout(function(){
                $("body").unbind("touchmove");
                $(".filter-mobile, #mobile-menu").hide();
                $(".b-menu-overlay").hide();
            },100);
        });
    }

    $(document).on('click', '.b-filter-submit', function(){
        filterSlideout.close();
        $('.b-menu-overlay').hide();
        $('.filter-mobile').removeClass('show-btn');
        return false;
    });

    if (!isDesktop) {
        $this = $('.b-filter');
        $('#mobile-menu').after($this);
        $('#mobile-menu').siblings('.b-filter').addClass('filter-mobile');
        var html = $('.filter-mobile').html();
        $('.filter-mobile').html('<h2>Фильтр</h2>' + html);
        $('.filter-mobile').after('<div class="b-btn-container"><a href="#" class="b-filter-submit b-btn">Применить</a></div>');
    }

    $(document).on('click', '.b-share-link a', function(){ 
        startOffsetY = window.pageYOffset;

        if ($('.b-share-link').hasClass('hover')) {
            $('.b-share-link').removeClass('hover');
        } else {
            $('.b-share-link').addClass('hover');
        }
        return false;
    });

    $(document).on('change', '.filter-mobile', function(){
        $(this).addClass('show-btn');
    });

    $(window).scroll(function(){

        currentOffsetY = window.pageYOffset;

        if (Math.abs(currentOffsetY - startOffsetY) >= 50) {
            $('.b-share-link').removeClass('hover');
        }
    });

/******************************************/

    $(".b-accordeon").each(function(){
        if( $(this).hasClass("opened") ){
            $(this).find(".b-accordeon-body").animate({
                height : "show",
                padding : "show"
            }, 300);
        }
    });

    $("body").on( "click", ".b-accordeon-plus", function(){
        var $accordeon = $(this).closest(".b-accordeon");
        if( $accordeon.hasClass("opened") ){
            $accordeon.children(".b-accordeon-body").animate({
                height : "hide",
                padding : "hide"
            }, 300);
            $accordeon.removeClass("opened");
        }else{
            $accordeon.children(".b-accordeon-body").animate({
                height : "show",
                padding : "show"
            }, 300);
            $accordeon.addClass("opened");
        }
    });

    $('.b-main-slider').slick({
        dots: ($(".b-main-slide").length > 1),
        dotsClass: "my-dots",
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 5000
    });

    catalogElementSlick();
    bindItemCards();

    $('.b-im-block').slick({
        dots: false,
        arrows: true,
        prevArrow: '<div class="icon-arrow-left" style="cursor: pointer; position: absolute; top: calc(50% - 34px); font-size: 70px; left: 35px; z-index: 100";></div>',
        nextArrow: '<div class="icon-arrow-right" style="cursor: pointer; position: absolute; top: calc(50% - 34px); font-size: 70px; right: 35px;"></div>',
        infinite: true,
        slidesToShow: 7,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: true,
        variableWidth: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    variableWidth: false,
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 768,
                settings: {
                    variableWidth: false,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 450,
                settings: {
                    variableWidth: false,
                    slidesToShow: 2
                }
            }
        ]
    });

    $('.delivery-methods-list').slick({
        infinite: true,
        prevArrow: '<div class="b-product-arrows icon-arrow-left-bold"></div>',
        nextArrow: '<div class="b-product-arrows icon-arrow-right-bold"></div>',
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 450,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    $(document).on('click', '.b-show-more', function(){
        $(this).addClass("hidden");
        $(this).prev(2).addClass("visible");
    });

    $(document).on('click', '.b-main-article', function(){
        window.location = $(this).find(".article-link").attr("href");
    });

    $(document).on('beforeChange', '.b-product-photo-slider', function(event, slick, currentSlide, nextSlide){
        var id = $(".b-product-photo-slider .img[data-slick-index='"+nextSlide+"']").attr('data-color-id');
        
        $(".colors-select option[data-color-id='"+id+"']").prop('selected', true);
        $('.colors-select').change().trigger('chosen:updated');
    });

    function showPhotoColor(id) {
        var slickID = $('.b-product-main a[data-color-id='+id+']').index();
        $('.b-product-main').slick('slickGoTo',parseInt(slickID));
    }

    $(".sort-select").chosen({
        width: "193px",
        disable_search_threshold: 10000
    });

    $(document).on('click', '.b-product-photo-slider.no-slider .img', function(){

        var id = $(this).attr('data-color-id');
        $('.texture-list .img[data-color-id='+id+']').click();
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        showPhotoColor(id);
    })

    chosenElementInit();

    $(document).on('change', '.colors-select', function(){

        if ($('.b-btn-to-cart').hasClass('hide')) {
            $('.b-btn-to-cart').removeClass('hide');
            $('.b-btn-to-cart-cap').addClass('hide');
        }

        var id = Number($(this).find(":selected").attr("data-color-id")),
            price = Number($(this).find(":selected").attr("data-price")),
            discountPrice = Number($(this).find(":selected").attr("data-discount-price")),
            article = $(this).find(":selected").attr("data-article"),
            quantity = Number($(this).find(":selected").attr("data-quantity")),
            measure = $(this).find(":selected").attr("data-measure"),
            inputVal = 1;

        if(price != discountPrice){
            if (!$('.b-product-params-right').hasClass('has-discount')) {
                $('.b-product-params-right').addClass('has-discount');
            }
        } else {
            if ($('.b-product-params-right').hasClass('has-discount')) {
                $('.b-product-params-right').removeClass('has-discount');
            }
        }

        if (quantity == 0) {
            inputVal = 0;
            $('.b-btn-to-cart').addClass('unavailable');
            $('.b-product-quantity').addClass('unavailable');
            $('.b-btn-to-cart-text').text('Товара нет в наличии');
        } else {
            $('.b-btn-to-cart').removeClass('unavailable');
            $('.b-product-quantity').removeClass('unavailable');
            $('.b-btn-to-cart-text').text('Добавить в корзину');
        }

        $('#price').text((new Intl.NumberFormat('ru-RU').format(price)).replace(/,/, '.'));
        $('#discount-price').text((new Intl.NumberFormat('ru-RU').format(discountPrice)).replace(/,/, '.'));
        $('#article').text(article);
        $('#quantity').text(quantity);
        $('#quantity-info').text(quantity);
        $('#measure').text(measure);
        $('input[name=count]').val(inputVal);
        $('input[name=count]').attr('data-quantity', quantity);
        $('.b-btn-to-cart').attr("data-id", id);
        $('input[name=count]').change();

        $(".texture-list .img.active").removeClass("active");
        $(".texture-list .img[data-color-id='"+id+"']").addClass("active");
        if(id > 10 && !$(".texture-list").hasClass("open")){
            $(".more-colors").click();
        }
        $(".b-product-photo-slider.no-slider .img.active").removeClass('active');
        $(".b-product-photo-slider.no-slider .img[data-color-id='"+id+"']").addClass('active');

        $(".b-product-photo-slider:not(.no-slider) .img[data-color-id='"+id+"']").click();
        showPhotoColor(id);

    });

    $(document).on('click', '.texture-list .img', function(){

        var id = Number($(this).attr("data-color-id"));
        $(".texture-list .img.active").removeClass("active");
        $(this).addClass("active");
        $(".colors-select option[data-color-id='"+id+"']").prop('selected', true);
        $('.colors-select').change().trigger('chosen:updated');
        showPhotoColor(id);

    });

    $(document).on('click','.more-colors', function() {
        if($(".texture-list").hasClass("open")){
            $(".texture-list").removeClass("open");
            $(this).text("Другие цвета");
        }else{
            $(".texture-list").addClass("open");
            $(this).text("Скрыть");
        }
        return false;
    });

    $(document).on('click','.show-more', function() {
        var $block = $(this).parents(".b-filter-toggle").find(".b-filter-more");
        if($block.hasClass("open")){
            $block.removeClass("open");
            $(this).text("смотреть больше");
        }else{
            $block.addClass("open");
            $(this).text("скрыть");
        }
        return false;
    });

    var maxBasketCount = 999;
    //увеличить количество
    $(document).on('click', '.b-product-quantity .quantity-add', function(){
        var $input = $('.quantity-input');

        if ($input.attr('data-quantity') != 0) {
            var count = parseInt($input.val()) + 1;
            count = (count > maxBasketCount || isNaN(count) === true) ? maxBasketCount : count;
            $input.val(count);
        }

        $input.change();
        return false;
    });
    //уменьшить количество
    $(document).on('click', '.b-product-quantity .quantity-reduce', function(){
        var $input = $('.quantity-input');
        var count = parseInt($input.val()) - 1; 
        count = (count < 1 || isNaN(count) === true) ? 1 : count;
        $input.val(count).change();
        return false;
    });

    $(document).on('change', '.b-product-quantity .quantity-input', function(){
        
        if($(this).val()*1 > $(this).attr('data-quantity')*1){
            $(this).val(Number($(this).attr('data-quantity')));
            if ($('.b-product-quantity-info').hasClass('hide')) {
                $('.b-product-quantity-info').removeClass('hide');
            }
        } else {
            if (!$('.b-product-quantity-info').hasClass('hide')) {
                $('.b-product-quantity-info').addClass('hide');
            }
        }

    });

    //табы
    $(document).on('click', '.tab', function(){
        var $this = $(this);
        $this.parent().find(".tab.active").removeClass("active");
        $this.addClass("active");
        $(".tabs-content").each(function(){
            $(this).addClass("hide");
        });
        $($this.attr("data-block")).removeClass("hide");
        return false;
    });

    $(document).on('change', '.lenght-input', function(){
        var min = $('.lenght-input:checked').attr('data-min'),
            max = $('.lenght-input:checked').attr('data-max');

        $('input[name=arrFilter_28_MIN]').val(min);
        $('input[name=arrFilter_28_MAX]').val(max);
    })

    $(".rating").hover(function() {
        $(this).addClass("now-hover");
    }, function() {
        $(this).removeClass("now-hover");
    });

    $(".rating-star").hover(function() {
        $(this).addClass("highlight-h");
        $(this).prevAll(".rating-star").addClass("highlight-h");
    }, function() {
        $(this).removeClass("highlight-h");
        $(this).prevAll(".rating-star").removeClass("highlight-h");
    });

    $(document).on('click', '.rating-star', function(){
        var $this = $(this);
        //здесь будет ajax-запрос
        $this.parent().find(".rating-star").each(function() {
            $(this).removeClass("highlight");
        });
        $this.addClass("highlight");
        $this.prevAll(".rating-star").addClass("highlight");
    });

    if( typeof autosize == "function" )
        autosize(document.querySelectorAll('textarea'));

    $(document).on('click', '.go-tab', function(){
        $($(this).attr("data-tab")).click();
        $("body, html").animate({scrollTop : $(".b-detail-tabs").offset().top-20}, 300);
        return false;
    });

    $(".b-filter-item").each(function() {
        if(!$(this).hasClass("open")){
            $(this).children(".b-filter-toggle").slideUp(0);
        }
    });
    $(document).on('click', '.b-filter-tab', function(){
        if(!$(this).hasClass("sliding")){
            var $this = $(this);
            $this.addClass("sliding");
            if($this.parent().hasClass("open")){
                $this.parent().removeClass("open");
                $this.parents(".b-filter-item").find(".b-filter-toggle").slideUp(300, function(){
                    $this.removeClass("sliding");
                });
            }else{
                $this.parent().addClass("open");
                $this.parents(".b-filter-item").find(".b-filter-toggle").slideDown(300, function(){
                    $this.removeClass("sliding");
                });
            }
        }  
        return false;
    });

    $( function() {
        $(".slider-range").each(function() {
            var $this = $(this),
                from = Number($(this).attr("data-range-from")),
                to = Number($(this).attr("data-range-to"));

            if($this.parent().find(".range-from").val() == '' || $this.parent().find(".range-from").val() == '0'){
                var fromVal = from,
                    toVal = to;
                $this.parent().find(".range-from").val(from);
                $this.parent().find(".range-to").val(to);
            } else {
                var fromVal = $this.parent().find(".range-from").val()*1,
                    toVal = $this.parent().find(".range-to").val()*1;
            }

            $this.slider({
                range: true,
                min: parseInt(from),
                max: Math.ceil(to),
                values: [parseInt(fromVal), Math.ceil(toVal)],
                slide: function( event, ui ) {
                    $this.parent().find(".range-from").val(parseInt(ui.values[0]));
                    $this.parent().find(".range-to").val(Math.ceil(ui.values[1]));
                },
                change: function( event, ui ){
                    $this.parents('form').change();
                }
            });
        });
    });

    var filterInterval = null;
    $(document).on('change', '.b-filter', function(){
        if (isDesktop) {

            if( filterInterval !== null ){
                clearTimeout(filterInterval);
            }

            ajaxFilter($(this));
            
            // filterInterval = setTimeout(ajaxFilter, 1500, $(this));
        }
    });

    $(document).on('change', '.sort-select', function(){
        var form = isDesktop ? $('.b-filter') : $('.filter-mobile');
        form.find('[name=SORT_FIELD]').val($(this).val());
        form.find('[name=SORT_TYPE]').val($(this).attr('data-type'));
        ajaxFilter(form);
    });

    function ajaxFilter(form){

        $('#PAGEN').val('1');
        window.history.replaceState(null , null, '?' + form.serialize() + "&set_filter=1");

        var url = window.location.href,
            block = $('.b-catalog-list');

        if( filterAjax !== null ){
            filterAjax.abort();
        }

        progress.start(1.5);
        if (block.hasClass('loaded')) {
            block.removeClass('loaded');
        }
        // $('.b-filter').addClass('load');

        filterAjax = $.ajax({
            type: "GET",
            url: url,
            data: { partial : true },
            success: function(msg){
                block.find('.b-catalog-list-cont').html(msg);
                cardHeight();
                bindFancy();
                updateFavFromLS();
            },
            error: function(){

            },
            complete: function(){
                progress.end();
                if (!block.hasClass('loaded')) {
                    block.addClass('loaded');
                }
                // $('.b-filter').removeClass('load');
            }
        });

    }

    $(document).on('change', '.range-from, .range-to', function(){
        var count = $(this).val()*1,
            $slider = $(this).parents(".b-filter-item-range").find(".slider-range");
            from = Number($slider.attr("data-range-from"));
            to = Number($slider.attr("data-range-to"));
        if($(this).hasClass("range-from")){
            var inputTo = $(this).siblings(".range-to").val()*1;
            count = (count > inputTo) ? inputTo : count;
        }else{
            var inputFrom = $(this).siblings(".range-from").val()*1;
            count = (count < inputFrom) ? inputFrom : count;
        }
        count = (count < from)? from : count;
        count = (count > to) ? to : count;
        $(this).val(count);
        var valCurrent =  $slider.slider( "option", "values" );
        if($(this).hasClass("range-from")){
            $slider.slider("option", "values", [count, valCurrent[1]]).trigger('slidechange');
        }else{
            $slider.slider("option", "values", [valCurrent[0], count]).trigger('slidechange');
        }
    });

    $('.b-btn-address').on('click', function(){
        if($('.js-order-adress-map-input').val()){
            $('.js-order-adress-map-input').removeClass("error");
        }else{
            $('.js-order-adress-map-input').addClass("error");
        }
        if($('#postal-code').val()){
            $('#postal-code-vue').val($('#postal-code').val());
            var e = supportedEvent("change");
            $('#postal-code-vue')[0].dispatchEvent(e);
            $('#postal-code').removeClass("error");
        }else{
            $('#postal-code').addClass("error");
        }
        if( $("#postal-code.error, .js-order-adress-map-input.error").length > 0 ){
            return false;
        }
        var room = "", postalCode = "";
        if($('#number-room-input').val()){
            room = ", кв. "+$('#number-room-input').val();
        }
        postalCode = $('#postal-code').val() + ", ";
        var resString = postalCode + $('.js-order-adress-map-input').val() + room;
        var $address = $("#app-order textarea[name='rdrdlvr']").val(resString);
        var e = supportedEvent("input");
        $address[0].dispatchEvent(e);
        $.fancybox.close();
        return false;
    });

    $('.js-order-adress-map-input').on('focus', function(){
        $('.js-order-adress-map-input').removeClass("error");
    });
     $('#postal-code').on('focus', function(){
        $('#postal-code').removeClass("error");
    });

    // добавление в корзину

    var cartTimeout = 0,
        successTimeout = 0;
    $("body").on("click", ".b-btn-to-cart", function(){

        if ($(this).hasClass('unavailable')) {
            return false;
        }

        var $this = $(this),
            $cap = $this.siblings('.b-btn-to-cart-cap'),
            href = $(this).attr("href"),
            id = $(this).attr("data-id"),
            quantity = $(this).parent().find('input[name=count]').val();
        
        clearTimeout(cartTimeout);
        progress.start(1.5);
        $cap.removeClass('hide').addClass('after-load');
        $this.addClass('hide');

        url = href+"&element_id="+id+"&quantity="+quantity;
        $.ajax({
            type: "GET",
            url: url,
            success: function(msg){
                progress.end();
                if( isValidJSON(msg) ){
                    var json = JSON.parse(msg);
                    if( json.result == "success" ){
                        if( json.action == "reload" ){
                            window.location.reload();
                        }else{
                            updateBasket(json.count, json.sum);
                        }
                        $cap.removeClass('error');
                        $cap.find('.b-cap-text').text('Товар успешно добавлен');
                    }else{
                        $cap.addClass('error');
                        $cap.find('.b-cap-text').text((json.error) ? json.error : 'Ошибка!');
                    }
                }else{
                    $cap.addClass('error');
                    $cap.find('.b-cap-text').text('Ошибка!');
                }
            },
            error: function(){
                $cap.addClass('error');
                $cap.find('.b-cap-text').text('Ошибка!');
            },
            complete : function(){
                $this.siblings('.b-btn-to-cart-cap').addClass('loaded');
                setTimeout(function(){
                    $cap.removeClass('loaded');
                    $cap.addClass('hide');
                    $this.removeClass('hide');
                }, 1500);
            }
        });
        return false;
    });

    $(document).on("click", ".element-view", function(){
        var url = $(this).attr('data-href')+"&element_view=Y";

        progress.start(1.5);
        
        $("#element_view .b-popup-element-cont").removeClass('after-load, loaded');
        $("#element_view .b-popup-element-cont").html('');
        $("#element_view .b-popup-element-cont").addClass('after-load');

        $.ajax({
            type: "GET",
            url: url,
            success: function(msg){
                progress.end();
                $("#element_view .b-popup-element-cont").html(msg);
                $("#element_view").find('.after-load').addClass('loaded');
                catalogElementSlick();
                chosenElementInit();
                bindFancy();
            },
            error: function(){

            }
        });
    })

    $(document).on('change', '.b-catalog-list .b-product-colors select', function(){
        $('.b-filter input[name=SORT_FIELD]').val($(this).find('option:selected').val());
        $('.b-filter input[name=SORT_TYPE]').val($(this).find('option:selected').attr('data-type'));
        // $('.b-filter').change();
    });

    $("body").on("click", ".b-order-item-icon .icon-close", function(){
        var url = $(this).attr('href'),
            $this = $(this);
        progress.start(1.5);

        $.ajax({
            type: "GET",
            url: url,
            success: function(msg){
                progress.end();
                if( isValidJSON(msg) ){
                    var json = JSON.parse(msg);
                    if( json.result == "success" ){
                        
                        if (Number($('.b-fav-number').text()) != 0) {
                            $('.b-fav-number').text(Number($('.b-fav-number').text()) - 1);
                        }
                        localStorage.setItem('favCount', Number($('.b-fav-number').text()));
                        setFavLS(json.arFav);
                        $this.parents('.b-order-item').remove();

                        if (Number($('.b-fav-number').text()) == 0) {
                            $('.b-fav-round').addClass('hide');
                        } else {
                            $('.b-fav-round').removeClass('hide');
                        }
                        
                    } else {
                        alert("Ошибка удаления");
                    }
                }else{
                    alert("Ошибка удаления");
                }
            },
            error: function(){
                progress.end();

            }
        });

        return false;

    })

    $("body").on("click", ".fav-link", function(){
        var url = $(this).attr('href')+"&action=FAVOURITE_"+$(this).attr('data-action'),
            $this = $(this);
        progress.start(1.5);

        var action = $(this).attr('data-action');
        if ($this.hasClass("active")){
            $this.attr('data-action', 'ADD');
            $this.removeClass("active");
        }else{
            $this.attr('data-action', 'REMOVE');
            $this.addClass("active");
        }

        $.ajax({
            type: "GET",
            url: url,
            success: function(msg){
                progress.end();
                if( isValidJSON(msg) ){
                    var json = JSON.parse(msg);
                    if( json.result == "success" ){
                        if (action == "REMOVE"){
                            if (Number($('.b-fav-number').text()) != 0) {
                                $('.b-fav-number').text(Number($('.b-fav-number').text()) - 1);
                            }
                        }else{
                            $('.b-fav-number').text(Number($('.b-fav-number').text()) + 1);
                        }
                        localStorage.setItem('favCount', Number($('.b-fav-number').text()));
                        setFavLS(json.arFav);

                        if (Number($('.b-fav-number').text()) == 0) {
                            $('.b-fav-round').addClass('hide');
                        } else {
                            $('.b-fav-round').removeClass('hide');
                        }
                    }
                }else{
                    if (action == "REMOVE"){
                        $this.removeClass("active");
                        $this.attr('data-action', 'ADD');
                    }else{
                        $this.addClass("active");
                        $this.attr('data-action', 'REMOVE');
                    }
                    alert("Ошибка добавления в избранное");
                }
            },
            error: function(){
                progress.end();
            }
        });

        return false;

    });

    if ($('#editForm').length){

        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles', // you can pass an id...
            container: document.getElementById('editForm'), // ... or DOM Element itself
            url : $('#editForm').attr("data-file-action"),
            multi_selection: true,
            filters : {
                max_file_size : '20mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,jpeg,gif,png"},
                    {title : "Documents", extensions : "doc,docx,pdf,rtf,xls,xlsx"},
                    {title : "Archive", extensions : "zip,rar,7z"},
                ]
            },
            init: {
                PostInit: function() {
                    
                },
                FilesAdded: function(up, files) {
                    progress.start(1.5);
                    plupload.each(files, function(file) {
                        
                    });
                    up.start();
                },
                UploadProgress: function(up, file) {
                    // $('.b-popup-add-link.icon-add-photo:before').css('content', '\e922');
                },
                FileUploaded: function(up, file, res) {
                    var json = JSON.parse(res.response);

                    // if ($('.current-photo img').length == 0) {
                    //     $('img').insertBefore('.current-photo .background-photo');  
                    // } 
                    
                    $('.current-photo').css('background-image', 'url("/upload/tmp/'+json.filePath+'")');

                    $('<input>',{id:'photo', type:'hidden', name:'user[PERSONAL_PHOTO]', value: json.filePath}).appendTo('#editForm');
                    // $('<div>',{class:'b-popup-add-photo', style:'background-image:url("/upload/tmp/'+json.filePath+'")'}).appendTo('#b-popup-add-photo-list');
                },
                Error: function(up, err) {
                    // alert("При загрузке файла произошла ошибка.\n" + err.code + ": " + err.message);
                    if (err.code == -600) {
                        $("#pickfiles").innerHTML = "Файл слишком большой";
                        $("#pickfiles").addClass('error');
                    };
                    if (err.code == -601) {
                        $("#pickfiles").innerHTML = "Неверный формат файла";
                        $("#pickfiles").addClass('error');
                    };
                },
                UploadComplete: function() {
                    progress.end();
                }
            }
        });
        uploader.init();
    }

    $('.fancybox-a').fancybox({'loop': true});
    $(document).on('click', '.b-reviews-list .review-more-a', function(){
        var el = $(this).attr('href');
        var popup = $(el).attr('href');
        var src = $(this).parents('li').find('.review-img').attr('src');
        var name = $(this).parents('li').find('.review-name').text();
        var text = $(this).parents('li').find('.review-text').text();
        $(popup).find('.popup-review-img').attr('src',src);
        $(popup).find('.popup-review-name').text(name);
        $(popup).find('.popup-review-text').text(text);
        $(el).click();
        return false;
    });

    // $(".b-card-top").height($(".b-card-top").width());


    // $(".b-step-slider").slick({
    //     dots: true,
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     infinite: true,
    //     cssEase: 'ease', 
    //     speed: 500,
    //     arrows: true,
    //     prevArrow: '<button type="button" class="slick-prev slick-arrow icon-arrow-left"></button>',
    //     nextArrow: '<button type="button" class="slick-next slick-arrow icon-arrow-right"></button>',
    //     touchThreshold: 100
    // });

    // // Первая анимация элементов в слайде
    // $(".b-step-slide[data-slick-index='0'] .slider-anim").addClass("show");

    // // Кастомные переключатели (тумблеры)
    // $(".b-step-slider").on('beforeChange', function(event, slick, currentSlide, nextSlide){
    //     $(".b-step-tabs li.active").removeClass("active");
    //     $(".b-step-tabs li").eq(nextSlide).addClass("active");
    // });

    // // Анимация элементов в слайде
    // $(".b-step-slider").on('afterChange', function(event, slick, currentSlide, nextSlide){
    //     $(".b-step-slide .slider-anim").removeClass("show");
    //     $(".b-step-slide[data-slick-index='"+currentSlide+"'] .slider-anim").addClass("show");
    // });


    if ($('#map_canvas').length != 0) {
        var myPlace = new google.maps.LatLng(56.504379, 84.945910);
        var myOptions = {
            zoom: 16,
            center: myPlace,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            scrollwheel: false,
            zoomControl: true
        }
        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); 

        var marker = new google.maps.Marker({
            position: myPlace,
            map: map,
            title: "Моточки-клубочки"
        });
    }

    bindFancy();
    loadBlock(0);

    $(document).on('click', '.popup-sign-list li a', function(){
        var el = $(this).attr('href');

        if ( !$(this).hasClass('active') ){
            $(this).parents('.popup-sign-list').find('a').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.popup-sign').find('.popup-sign-form').removeClass('active');
            $(el).addClass('active');
        }
        return false;
    });

    // $('.mobile-btn').on('click',function(){
    //     $('.mobile-menu').addClass('active');
    //     $('.mobile-menu-bg').addClass('active');
    //     $('body').addClass('no-scroll');
    //     return false;
    // });

    // $('.mobile-menu-close-btn').on('click',function(){
    //     $('.mobile-menu').removeClass('active');
    //     $('.mobile-menu-bg').removeClass('active');
    //     $('body').removeClass('no-scroll');
    //     return false;
    // });
    // $('.mobile-menu-bg').on('click',function(){
    //     $('.mobile-menu').removeClass('active');
    //     $('.mobile-menu-bg').removeClass('active');
    //     $('body').removeClass('no-scroll');
    //     return false;
    // });

    $('.b-text table').each(function(){
        $(this).wrap("<div class='b-table-wrap'></div>");
    });

    if ($('.b-filter-cont').height() == 0 || $('.b-filter').hasClass('no-filter')) {
        $('.b-filter-cont').addClass('no-filter');
    }

    if (($('#SECTION_CODE').val() == 'novoe-postuplenie') || ($('#SECTION_CODE').val() == 'aktsii-i-skidki')) {
        $('.catalog-mobile-filter').addClass('no-filter');
    }

    detailInit();

    updateFavFromLS();

    // cardImgHeight();
});

function supportedEvent(eventName) {
    var e;
    if(typeof(Event) === 'function') {
        e = new Event(eventName);
    }else{
        e = document.createEvent('Event');
        e.initEvent(eventName, true, true);
    }
    return e;
}

function updateBasket(count, sum){
    $(".b-cart-number").text( count.toLocaleString() );
    $(".b-cart-price").text( sum.toLocaleString() );
    localStorage.setItem('count', count.toLocaleString());
    localStorage.setItem('sum', sum.toLocaleString());
}

function isValidJSON(src) {
    var filtered = src+"";
    filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
    filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
    filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');

    return (/^[\],:{}\s]*$/.test(filtered));
}

function updateFav(arFav) {
    $(".fav-link").each(function(){//сбросить все избранное
        $(this).attr("data-action", "ADD").removeClass("active");
    });
    arFav.forEach(function(item, i, arr) {//проставить актуальные
        $(".fav-link[data-id='"+item+"']").attr("data-action", "REMOVE").addClass("active");
    });
}
function updateFavFromLS() {
    var favLocalStorage = getFavLS();
    if(favLocalStorage){
        updateFav(favLocalStorage);
    }
}
function getFavLS() {
    return JSON.parse(localStorage.getItem("arFav"));
}
function setFavLS(arFav) {
    localStorage.setItem("arFav", JSON.stringify(arFav));
}

if(localStorage.getItem('auth') && localStorage.getItem('auth') === "true"){
    $("body").addClass("auth-before");
}else{
    $("body").addClass("no-auth-before");
}

if(localStorage.getItem('userName') && localStorage.getItem('userName') !== ""){
    $(".mobile-menu-user").text(localStorage.getItem('userName'));
}else{
    $(".mobile-menu-user").text("");
}

//Получить избранные товары
$.ajax({
    type: "GET",
    url: "/ajax/index.php?action=COMPOSITE",
    success: function(msg){
        if( isValidJSON(msg) ){
            var json = JSON.parse(msg);
            if( json.result == "success" ){
                //сумма и количество
                updateBasket(json.count, json.sum);
                //количество избранного
                var favCount = json.favCount;
                localStorage.setItem('favCount', favCount);
                if (favCount > 0) {
                    $(".b-fav-number").text(favCount);
                    $(".b-fav-round").removeClass("hide");
                } else {
                    $(".b-fav-number").text("0");
                    $(".b-fav-round").addClass("hide");
                }
                if(json.isAuth){
                    setFavLS(json.arFav);
                    localStorage.setItem('userName', json.userName);
                    $(".mobile-menu-user").text(json.userName);
                    updateFav(json.arFav);
                    $("body").addClass("auth").removeClass("auth-before no-auth-before");
                    localStorage.setItem('auth', true);
                }else{
                    $("body").addClass("no-auth").removeClass("auth-before no-auth-before");
                    localStorage.setItem('auth', false);
                }
            }
        }
    },
    error: function(){
        
    },
});

// Иницализация элементов на каталоге на главной
if (window.frameCacheVars !== undefined && window.frameCacheVars.dynamicBlocks.slider_component_1 !== undefined) {
    BX.addCustomEvent("onFrameDataReceived", mainCatalogInit);
}

function mainCatalogInit() {
    console.log('mainCatalogInit');
    bindItemCards();
    cardHeight();

    $(".b-item-cards.slick-slider").find(".b-item-card").each(function(){
        var $height = $(this).parents(".slick-track").innerHeight();
        $(this).innerHeight($height);
    });
}

// Иницализация элементов на детальной
if (window.frameCacheVars !== undefined && window.frameCacheVars.dynamicBlocks.detail_component !== undefined) {
    BX.addCustomEvent("onFrameDataReceived", function(json){
        setTimeout(detailInit, 10);
    });
}

function detailInit() {
    console.log('detailInit');

    catalogElementSlick();
    bindItemCards();
    chosenElementInit();

    if ($('.b-product-main').length) {
        $(".b-product-main a").fancybox({ 
            animationEffect : 'fade'
        }).attr('data-fancybox', 'gallery');
    }
    $(".b-item-cards.slick-slider").find(".b-item-card").each(function(){
        var $height = $(this).parents(".slick-track").innerHeight();
        $(this).innerHeight($height);
    });

    bindFancy();
    loadBlock(0);

    updateFavFromLS();
}

function catalogElementSlick(){

    var asNavFor = '.b-product-photo-slider';

    if ($('.b-product-photo-slider').hasClass('no-slider') || $('.b-product-photo-slider').length == 0) {
        asNavFor = '';
    }

    $('.b-product-main:not(.slick-initialized)').each(function(){
        $(this).not('.slick-initialized').slick({
            dots: false,
            arrows: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            asNavFor: asNavFor,
            swipe: false,
            cssEase: 'linear',
            speed: 100,
            fade: true,
        });
    })

    $('.b-product-photo-slider:not(.no-slider)').each(function(){
        $(this).not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            prevArrow: '<div class="b-product-arrows icon-arrow-left-bold"></div>',
            nextArrow: '<div class="b-product-arrows icon-arrow-right-bold"></div>',
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: false,
            variableWidth: true,
            asNavFor: '.b-product-main',
            focusOnSelect: true,
            responsive: [
                {
                  breakpoint: 1188,
                  settings: {
                    slidesToShow: 3
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 5
                  }
                },
                {
                  breakpoint: 665,
                  settings: {
                    slidesToShow: 4
                  }
                },
                {
                  breakpoint: 374,
                  settings: {
                    slidesToShow: 3
                  }
                }
            ]
        });
    });
}

function bindItemCards() {
    $('.b-item-cards:not(.slick-initialized)').each(function(){
        $(this).slick({
            dots: false,
            arrows: true,
            prevArrow: '<div class="icon-arrow-left"></div>',
            nextArrow: '<div class="icon-arrow-right"></div>',
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: true,
            autoplaySpeed: getRandomInt(3000, 5000),
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 450,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
}
function getRandomInt(min, max) {
    return Math.round(Math.random() * (max - min)) + min;
}

function chosenElementInit(){
    $(".colors-select").chosen({
        width: "193px",
        disable_search_threshold: 10000
    });
}

function bindFancy(){
    $(".fancy:not(.fancy-binded)").each(function(){
        var $popup = $($(this).attr("href")),
            $this = $(this);
        $this.fancybox({
            padding : 0,
            content : $popup,
            touch: false,
            helpers: {
                overlay: {
                    locked: true 
                }
            },
            beforeShow: function(){
                $(".fancybox-wrap").addClass("beforeShow");
                $popup.find(".custom-field").remove();
                if( $this.attr("data-value") ){
                    var name = getNextField($popup.find("form"));
                    $popup.find("form").append("<input type='hidden' class='custom-field' name='"+name+"' value='"+$this.attr("data-value")+"'/><input type='hidden' class='custom-field' name='"+name+"-name' value='"+$this.attr("data-name")+"'/>");
                }
                if( $popup.attr("data-beforeShow") && customHandlers[$popup.attr("data-beforeShow")] ){
                    customHandlers[$popup.attr("data-beforeShow")]($popup);
                }
            },
            afterShow: function(){
                $(".fancybox-wrap").removeClass("beforeShow");
                $(".fancybox-wrap").addClass("afterShow");
                if( $popup.attr("data-afterShow") && customHandlers[$popup.attr("data-afterShow")] ){
                    customHandlers[$popup.attr("data-afterShow")]($popup);
                }
                $popup.find("input[type='text'],input[type='number'],textarea").eq(0).focus();
            },
            beforeClose: function(){
                $(".fancybox-wrap").removeClass("afterShow");
                $(".fancybox-wrap").addClass("beforeClose");
                if( $popup.attr("data-beforeClose") && customHandlers[$popup.attr("data-beforeClose")] ){
                    customHandlers[$popup.attr("data-beforeClose")]($popup);
                }
            },
            afterClose: function(){
                $(".fancybox-wrap").removeClass("beforeClose");
                $(".fancybox-wrap").addClass("afterClose");
                if( $popup.attr("data-afterClose") && customHandlers[$popup.attr("data-afterClose")] ){
                    customHandlers[$popup.attr("data-afterClose")]($popup);
                }
            }
        });
        $this.addClass("fancy-binded");
    });

    $(".fancy-img:not(.fancy-binded)").each(function(){
        $(this).fancybox({
            padding : 0,
            hash : false,
            clickContent : false,
            buttons : [
                'fullScreen',
                'close'
            ],
        });
        $(this).addClass('fancy-binded');
    });
}

window.onload = function(){
    cardHeight();
    // cardImgHeight();
};

function loadBlock(i){
    var length = $('.after-load').length;
    if (i > length) {
        return false;
    } else {
        $('.after-load').eq(i).addClass('loaded');
        i++;

        setTimeout(function(){
            loadBlock(i);
        },50);
    }
}

function isIE() {
    var rv = -1;
    if (navigator.appName == 'Microsoft Internet Explorer')
    {
        var ua = navigator.userAgent;
        var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat( RegExp.$1 );
    }
    else if (navigator.appName == 'Netscape')
    {
        var ua = navigator.userAgent;
        var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat( RegExp.$1 );
    }

    var isIE = false;

    if (rv !== -1) {
        var isIE = true;
        $('html').addClass('ie');
    }

    return isIE;
}

function cardHeight(){
    if (isIE()) {
        $(document).find(".b-item-card").each(function(){
            if ($(".b-catalog-list .b-item-card").length == 1) {
                return false;
            }

            var cardsInString = 4;

            var $index = $(this).index() + 1,
                $height = $(this).height(),
                $count = $(this).parents('.b-catalog-list').find('.b-item-card').length;

            if ($index == 1) {
                $maxHeight = 0;
            }

            if ($height > $maxHeight) {
                $maxHeight = $height;
            }

            if ($index % cardsInString == 0) {
                for (var i = 0; i < cardsInString; i++) {
                    $('.b-catalog-list .b-item-card:nth-child('+($index - i)+')').height(Math.round($maxHeight));   
                }
                $maxHeight = 0;
            } else {
                if (($count - $index) == 0) {
                    while($index % cardsInString != 0){
                        $('.b-catalog-list .b-item-card:nth-child('+$index+')').height(Math.round($maxHeight));
                        $index = $index - 1;
                    }
                }
            }
        });
    }
}

function cardImgHeight(){
    $('.b-item-card').each(function(){
        var $this = $(this).find('.b-card-top img');
        $this.height($this.width());
    });
}


