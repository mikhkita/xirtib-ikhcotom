function getNextField($form){
	var j = 1;
	while( $form.find("input[name="+j+"]").length ){
		j++;
	}
	return j;
}

function fancyOpen(el){
    $.fancybox(el,{
    	padding:0,
    	fitToView: false,
        scrolling: 'no',
        beforeShow: function(){
			$(".fancybox-wrap").addClass("beforeShow");
			if( !device.mobile() ){
		    	$('html').addClass('fancybox-lock'); 
		    	$('.fancybox-overlay').html($('.fancybox-wrap')); 
		    }
		},
		afterShow: function(){
			$(".fancybox-wrap").removeClass("beforeShow");
			$(".fancybox-wrap").addClass("afterShow");
			setTimeout(function(){
                $('.fancybox-wrap').css({
                    'position':'absolute'
                });
                $('.fancybox-inner').css('height','auto');
            },200);
		},
		beforeClose: function(){
			$(".fancybox-wrap").removeClass("afterShow");
			$(".fancybox-wrap").addClass("beforeClose");
		},
		afterClose: function(){
			$(".fancybox-wrap").removeClass("beforeClose");
			$(".fancybox-wrap").addClass("afterClose");
		},
    }); 
    return false;
}

var customHandlers = [];

$(document).ready(function(){	
	var rePhone = /^\+\d \(\d{3}\) \d{3}\-\d{2}\-\d{2}$/,
		tePhone = '+7 (999) 999 9999';

	$.validator.addMethod('customPhone', function (value) {
		return rePhone.test(value);
	});

	$(".ajax, .not-ajax").parents("form").each(function(){
		$(this).validate({
			onkeyup: (!$(this).hasClass("b-data-order-form"))?false:true,
			rules: {
				email: 'email',
				phone: 'customPhone',
				'user[PERSONAL_PHONE]': 'customPhone',
				ORDER_PROP_3: 'email',
				ORDER_PROP_4: 'customPhone',
			},
			errorPlacement: function(error, element) {
                error.appendTo(element.parents(".b-review-input").addClass("error"));
            },
            success: function(label) {
			    label.parents(".b-review-input").removeClass("error");
			},
			errorElement : "span",
			highlight: function(element, errorClass) {
			    $(element).addClass("error").parents(".b-input").addClass("error");
			},
			unhighlight: function(element) {
			    $(element).removeClass("error").parents(".b-input").removeClass("error");
			}
		});
		if( $(this).find("input[name=phone], input[name=tel], input[name=addressee-phone], input[name=ORDER_PROP_4], input[name=PERSONAL_PHONE], input[name='user[PERSONAL_PHONE]']").length ){
			$(this).find("input[name=phone], input[name=tel], input[name=addressee-phone], input[name=ORDER_PROP_4], input[name=PERSONAL_PHONE], input[name='user[PERSONAL_PHONE]']").each(function(){
				if (typeof IMask == 'function') {
					var phoneMask = new IMask($(this)[0], {
			        	mask: '+{7} (000) 000-00-00',
			        	prepare: function(value, masked){
					    	if( value == 8 && masked._value.length == 0 ){
					    		return "+7 (";
					    	}

					    	tmp = value.match(/[\d\+]*/g);
					    	if( tmp && tmp.length ){
					    		value = tmp.join("");
					    	}else{
					    		value = "";
					    	}
					    	return value;
					    }
			        });
				} else {
					$(this).mask("+7 (999) 999-99-99");
				}
			});
		}

		if( $(this).hasClass("b-data-order-form") ){
			$(this).find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").blur(function(){
			   // $(this).valid();
			});

			$(this).find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").keyup(function(){
			   // $(this).valid();
			});
		}
	});

	function whenScroll(){
		var scroll = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
		if( customHandlers["onScroll"] ){
			customHandlers["onScroll"](scroll);
		}
	}
	$(window).scroll(whenScroll);
	whenScroll();
	bindFancy();
	
	function bindFancy(){
		$(".fancy:not(.fancy-binded)").each(function(){
			var $popup = $($(this).attr("href")),
				$this = $(this);
			$this.fancybox({
				padding : 0,
				content : $popup,
				touch: false,
				backFocus: false,
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
	}

	var open = false;
    $("body").on("mouseup", ".b-popup *, .b-popup", function(){
        open = true;
    });
    $("body").on("mousedown", ".fancybox-slide", function() {
        open = false;
    }).on("mouseup", ".fancybox-slide", function(){
        if( !open ){
            $.fancybox.close();
        }
    });

	$(".b-go").click(function(){
		var block = $( $(this).attr("data-block") ),
			off = $(this).attr("data-offset")||0,
			duration = $(this).attr("data-duration")||800;
		$("body, html").animate({
			scrollTop : block.offset().top-off
		},duration);
		return false;
	});

	// $(".fancy-img").fancybox({
	// 	padding : 0,
	// 	hash : false,
	// 	clickContent : false,
	// 	buttons : [
	//         'fullScreen',
	//         'close'
	//     ],
	// });

	$(".goal-click").click(function(){
		if( $(this).attr("data-goal") && typeof ym != "undefined" ){
			ym(36653305, 'reachGoal', $(this).attr("data-goal"));
		}
	});

	$("body").on("submit", "form", function(){
		if( $(this).find(".ajax, .not-ajax").length ){
			var $form = $(this);

			$(".b-postamat-error").remove();

			if ($form.attr('id') == 'editForm') {

				$form.find('.b-btn-save').parent().addClass('after-load');

				if ($('#change_pass').prop('checked') == true){
					$form.find('.pass-error').addClass('hide').html('');
					var html = '';
					var error = false;

					if($form.find("#pass").val() !== $form.find("#confpass").val()){
						error = true;
						$form.find("#confpass").addClass('error');
						html = '<p>Введённые пароли не&nbsp;совпадают</p>';
					}

					if($form.find("#pass").val().length < 6){
						error = true;
						$form.find("#pass").addClass('error');
						html = '<p>Минимальная длина пароля - 6&nbsp;символов</p>' + html;
					}

					if (error) {
						$form.find('.pass-error').removeClass('hide').html(html);
						$form.find('.b-btn-save').parent().removeClass('after-load');
					}
				} else {
					$form.find('.pass-error').addClass('hide');
				}
			}

			// if( $form.hasClass("b-data-order-form") && $(".b-pickpoint").is(":visible") && !$(".pickpointaddr").length ){
			// 	$(".b-add-postamat").after("<p class='red b-postamat-error'>Вам нужно выбрать постамат, в котором вы хотите получить вашу посылку.</p>");
			// }
			// alert($form.is("#b-order-form"));
			// alert($("input[name='delivery']:cheched").val());
			// alert($(".cdekaddr").length);


			if( $form.is("#b-order-form") && $("input[name='delivery']:cheched").val() == "15" && !$(".cdekaddr").length ){
				$(".b-cdek-punkt").after("<p class='red b-postamat-error'>Вам нужно выбрать пункт самовывоза, в котором вы хотите получить вашу посылку.</p>");
			}

	  		if( $(this).find("input.error,select.error,textarea.error,.b-postamat-error").length == 0 ){
	  			var $this = $(this),
	  				$thanks = $($this.attr("data-block"));

	  			if( $(this).find(".not-ajax").length ){
	  				if( $("select#date").length ){
	  					$("select#date").prop("disabled", false);
	  				}
	  				if( $(this).is("#ORDER_FORM") ){
	  					// alert();
	  					$(".basket-checkout-block-btn").addClass("loading");
	  					$("#b-basket-checkout-button").after("<p class='b-order-submit-message'>Подождите, идет создание заказа</p>");
	  				}
	  				return true;
	  			}

	  			$this.find(".ajax").attr("onclick", "return false;");

	  			if( $this.attr("data-beforeAjax") && customHandlers[$this.attr("data-beforeAjax")] ){
					customHandlers[$this.attr("data-beforeAjax")]($this);
				}

				if( $this.attr("data-goal") && typeof ym != "undefined" ){
					ym(36653305, 'reachGoal', $(this).attr("data-goal"));
				}

	  			$.ajax({
				  	type: $(this).attr("method"),
				  	url: $(this).attr("action"),
				  	data:  $this.serialize(),
					success: function(msg){

						if( isValidJSON(msg) && msg != "1" && msg != "0" && msg !=''){

							var json = JSON.parse(msg);

							if( json.result == "success" ){
								if(json.userData){
									localStorage.setItem('count', json.userData.count);
	    							localStorage.setItem('sum', json.userData.sum);
	    							localStorage.setItem('favCount', json.userData.favCount);
	    							localStorage.setItem('auth', json.userData.isAuth);
	    							if(json.userData.arFav){
	    								localStorage.setItem("arFav", JSON.stringify(json.userData.arFav));
	    							}
	                    			localStorage.setItem('userName', json.userData.userName);
								}
					            switch (json.action) {
					                case "reload":
					                    document.location.reload(true);
					                    $.fancybox.close();
					                break;
					                case "redirect":
					                    document.location.href = json.redirect;
					                    $.fancybox.close();
					                break;
					            }
					        }else{
					        	$form.find(".b-popup-error").html(json.error);
					        	switch (json.action) {
					                case "messageError":
					                    $form.find(".b-popup-error").html(json.message);
					                break;
					            }
					        }

						}else{
							if( msg == "1" ){
								$link = $this.find(".b-thanks-link");
							}else{
								if ($form.attr('id') == 'editForm') {
									$form.find('.b-btn-save').parent().removeClass('after-load');
								}
								$link = $this.find(".b-error-link");
							}

							if( $this.attr("data-afterAjax") && customHandlers[$this.attr("data-afterAjax")] ){
								customHandlers[$this.attr("data-afterAjax")]($this);
							}

							$.fancybox.close();
							$link.click();
						}
					},
					error: function(){
						$.fancybox.close();
						$this.find(".b-error-link").click();
					},
					complete: function(){
						$this.find(".ajax").removeAttr("onclick");
						if( !$this.is("#b-form-auth") && !$this.is("#editForm") && !$this.is("#regForm") ){
							$this.find("input[type=text],textarea").val("");
						}
					}
				});
	  		}else{
	  			$(this).find("input.error,select.error,textarea.error").eq(0).focus();
	  		}
	  		return false;
		}
	});

	$("body").on("click", ".ajax, .not-ajax", function(){
		$(this).parents("form").submit();
		return false;
	});

	function isValidJSON(src) {
        var filtered = src;
        filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
        filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
        filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');

        return (/^[\],:{}\s]*$/.test(filtered));
    }
});