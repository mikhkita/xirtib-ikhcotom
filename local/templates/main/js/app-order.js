(function() {

    var myWidth;

    function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function formatNumberExternal(number) {
        return String(number).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, '$1 ');
    }
    function windowResize (event) {
        if( typeof( window.innerWidth ) == 'number' ) {
            myWidth = window.innerWidth;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myWidth = document.documentElement.clientWidth;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myWidth = document.body.clientWidth;
        }

        if(myWidth < 768){
            $(".b-order-totals").trigger("sticky_kit:detach");
        }else{
            $(".b-order-totals").stick_in_parent({offset_top: 24});
        }
    };

    // ===== Дерево компонентов =====

    // v-order
    //   |--v-order-list
    //      |--v-order-item
    //   |--v-totals

    // ==============================

    Vue.use(VeeValidate);
    Vue.component('v-order',{
        data: function () {
            return {
                orders: [],
                isAuth: false,
                show: false,
                showCatalogRef: false,
                showPreloader: true,
                couponList: [],
                form: {
                    name: "",
                    phone: "",
                    email: "",
                    deliveryActive: "",
                    deliveryList: [],
                    paymentActive: "",
                    paymentList: [],
                    address: "",
                    comment: "",
                    blockedSubmit: false,
                    nowSubmit: false
                },
                delayQuantity: 300,
                timeoutQuantity: null,
                countQueue: 0,
                pluginsInit: false,
                sdekInit: false
            }
        },
        mounted: function () {
          if(dataOrder){
            if(dataOrder.items){
                this.orders = dataOrder.items;
                this.show = true;
                this.showPreloader = false;
            }
            if(dataOrder.coupons){
                this.couponList = dataOrder.coupons;
            }
            if(dataOrder.delivery){
                this.form.deliveryList = dataOrder.delivery;
                this.form.deliveryActive = this.form.deliveryList[0].id;
                //обнулить стоимость доставки для всех типов кроме "Настраиваемая служба доставки"
                for (i = 0; i < this.form.deliveryList.length; i++) {
                    if(!this.form.deliveryList[i].fixedCost){
                        this.form.deliveryList[i].cost = 0;
                    }
                }
            }
            if(dataOrder.payments){
                this.form.paymentList = dataOrder.payments;
                this.form.paymentActive = this.form.paymentList[0].id;
            }
            if(dataOrder.isAuth){
                this.isAuth = dataOrder.isAuth;
                if(dataOrder.user){
                    this.form.name = dataOrder.user.name;
                    this.form.phone = dataOrder.user.phone;
                    this.form.email = dataOrder.user.email;
                }
            }
          }
          if(this.orders.length === 0){
              this.show = false;
              this.showCatalogRef = true;
          }

          console.log(this.orders);
          //setTimeout(function() { 
            // $.ajax({
            //     type: "get",
            //     url: "send/getOrderList.php",
            //     success: function(response){
            //       if(response){
            //         var data = JSON.parse(response);
            //         if(data.items){
            //             self.orders = data.items;
            //             self.show = true;
            //             self.showPreloader = false;
            //         }
            //         if(data.coupons){
            //             self.couponList = data.coupons;
            //         }
            //         if(data.delivery){
            //             self.form.deliveryList = data.delivery;
            //             self.form.deliveryActive = self.form.deliveryList[0].value;
            //         }
            //         if(data.payments){
            //             self.form.paymentList = data.payments;
            //             self.form.paymentActive = self.form.paymentList[0].value;
            //         }
            //         if(data.isAuth){
            //             self.isAuth = data.isAuth;
            //         }
            //       }
            //     },
            //     error: function(){}
            // });
        },
        template: '\
        <div>\
            <div v-if="show" class="b-order">\
                <div class="b-order-left">\
                    <v-order-list \
                        @onChangeQuantity="changeQuantity"\
                        @onRemoveItem="removeItem"\
                        @onFavoriteToggle="favoriteToggle"\
                        @onRemoveWarning="removeWarning"\
                        :orders="orders"\
                        :isAuth="isAuth"\
                    ></v-order-list>\
                    <form id="b-order-form" class="b-order-form" method="post" action="success.php">\
                        <h3>Данные к заказу</h3>\
                        <div class="b-inputs-3 clearfix">\
                            <div class="b-input">\
                                <p>Ф.И.О.</p>\
                                <input \
                                    type="text" \
                                    name="name" \
                                    placeholder="Как вас зовут?"\
                                    v-model="form.name"\
                                    v-validate="\'required\'"\
                                    :class="{ error: errors.first(\'name\')}"\
                                >\
                            </div>\
                            <div class="b-input">\
                                <p>Номер телефона</p>\
                                <input \
                                    type="text" \
                                    name="phone" \
                                    placeholder="+7 (999) 999 0000"\
                                    v-model="form.phone"\
                                    v-validate="{ required: true, regex: /^\\+\\d \\(\\d{3}\\) \\d{3}-\\d{2}-\\d{2}$/ }"\
                                    :class="{ error: errors.first(\'phone\')}"\
                                >\
                                \
                            </div>\
                            <div class="b-input">\
                                <p>Электронная почта</p>\
                                <input \
                                    type="text" \
                                    name="email" \
                                    placeholder="example@yandex.ru"\
                                    v-model="form.email"\
                                    v-validate="\'required|email\'"\
                                    :class="{ error: errors.first(\'email\')}"\
                                >\
                                \
                            </div>\
                        </div>\
                        <div class="b-choice clearfix">\
                          <div class="b-delivery clearfix">\
                            <h4>Способ доставки</h4>\
                            <ul class="b-radio">\
                                <li v-for="delivery in form.deliveryList" :key="delivery.id">\
                                    <input\
                                        :id="getLabel(\'delivery\', delivery.id)"\
                                        type="radio"\
                                        name="delivery"\
                                        :checked="form.deliveryActive == delivery.id"\
                                        v-model="form.deliveryActive"\
                                        :value="delivery.id"\
                                        @change="calcDelivery"\
                                    >\
                                    <label :for="getLabel(\'delivery\', delivery.id)">{{ delivery.name }}</label>\
                                </li>\
                            </ul>\
                            <ul class="b-delivery-tabs">\
                                <li v-for="delivery in form.deliveryList" :key="delivery.id" v-show="form.deliveryActive == delivery.id" v-html="delivery.text">\
                                </li>\
                            </ul>\
                          </div>\
                          <div class="b-pay">\
                            <h4>Способ оплаты</h4>\
                            <ul class="b-radio">\
                                <li v-for="payment in form.paymentList" :key="payment.id">\
                                    <input\
                                        :id="getLabel(\'payment\', payment.id)"\
                                        type="radio"\
                                        name="payment"\
                                        :checked="form.paymentActive == payment.id"\
                                        v-model="form.paymentActive"\
                                        :value="payment.id"\
                                    >\
                                    <label :for="getLabel(\'payment\', payment.id)">{{ payment.name }}</label>\
                                </li>\
                            </ul>\
                          </div>\
                        </div>\
                        <div class="b-order-form-bottom">\
                            <div class="b-order-sdek-map" v-show="form.deliveryActive == \'15\'"></div>\
                            <div class="b-order-address-input" v-show="form.deliveryActive != \'15\'">\
                                <div v-if="form.deliveryActive != \'5\' && form.deliveryActive != \'15\'">\
                                    <div class="b-textarea">\
                                        <p>Адрес доставки</p>\
                                        <textarea rows="1" name="addr" autocomplete="off" placeholder="Введите адрес" v-model="form.address"\
                                            v-validate="\'required\'"\
                                            :class="{ error: errors.first(\'address\')}"\
                                            @focus="openMap"\
                                        ></textarea>\
                                    </div>\
                                </div>\
                            </div>\
                            <input id="postal-code-vue" type="hidden" name="postal-code-vue" @change="calcDelivery">\
                            <input id="delivery-cost" type="hidden" name="delivery-cost" @change="changeCost">\
                            <div class="b-textarea">\
                                <p>Комментарий к заказу</p>\
                                <textarea rows="1" name="comment" placeholder="Введите комментарий" v-model="form.comment"></textarea>\
                            </div>\
                        </div>\
                    </form>\
                    <div class="order-submit-desktop">\
                        <a href="#" class="b-btn b-btn-order-submit" @click.prevent="validationForm" v-if="!form.blockedSubmit && !form.nowSubmit">Оформить заказ</a>\
                        <div class="b-btn-blocked-cont" v-else>\
                            <span class="b-btn b-btn-blocked">Оформить заказ</span>\
                            <div class="b-btn-blocked-preloader">\
                                <img src="/local/templates/main/i/preloader.svg">\
                                <span v-if="!form.nowSubmit">Идет расчет стоимости доставки, пожалуйста, подождите</span>\
                                <span v-else>Выполняется оформление заказа, пожалуйста, подождите</span>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                <v-totals\
                    @onUpdateOrder="updateOrder"\
                    @onUpdateCoupons="updateCoupons"\
                    :_rawBase="rawBase"\
                    :_rawTotal="rawTotal"\
                    :_discount="discount"\
                    :_delivery="delivery"\
                    :_total="total"\
                    :_couponList="couponList"\
                ></v-totals>\
                <div class="order-submit-mobile">\
                    <a href="#" class="b-btn b-btn-order-submit" @click.prevent="validationForm" v-if="!form.blockedSubmit && !form.nowSubmit">Оформить заказ</a>\
                    <div class="b-btn-blocked-cont" v-else>\
                        <span class="b-btn b-btn-blocked">Оформить заказ</span>\
                        <div class="b-btn-blocked-preloader">\
                            <img src="/local/templates/main/i/preloader.svg">\
                            <span v-if="!form.nowSubmit">Идет расчет стоимости доставки, пожалуйста, подождите</span>\
                            <span v-else>Выполняется оформление заказа, пожалуйста, подождите</span>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            \
            <div v-if="showCatalogRef">\
              <span>Ваша корзина пуста. </span><a class="dashed" href="/catalog/">Перейти в каталог</a>\
            </div>\
            \
            <div v-if="showPreloader" class="b-order-preloader">\
              <img src="/local/templates/main/i/preloader.svg">\
            </div>\
        </div>\
        ',
        methods: {
            getLabel: function (block, value) {
                return "label-"+block+"-"+value;
            },
            changeQuantity: function (id, quantity) {
                var self = this;
                self.orders.filter(function(v) {return v.id === id})[0].quantity = quantity;
                if(self.timeoutQuantity){
                    clearTimeout(self.timeoutQuantity);
                }
                self.timeoutQuantity = setTimeout(function () {
                    self.countQueue++;
                    $.ajax({
                        type: "get",
                        url: "/ajax/index.php",
                        data: {"ELEMENT_ID": id, "QUANTITY": quantity, "action": "QUANTITY"},
                        success: function(response){
                            var data = JSON.parse(response);
                            if(data.result === "success"){
                                self.countQueue--;
                                if(self.countQueue == 0){
                                    self.orders.filter(function(v) {return v.id === data.id})[0].quantity = data.quantity;
                                }
                            }else{
                                alert("Ошибка изменения количеста, пожалуйста, обновите страницу");
                            }
                        },
                        error: function(){
                            self.countQueue--;
                        },
                        complete: function(){
                            self.calcDelivery();//пересчет суммы доставки
                        }
                    });
                }, self.delayQuantity);
            },
            removeItem: function (id) {
                var self = this,
                    index = self.orders.map(function(v) {return v.id}).indexOf(id);
                self.orders[index].visible = false;//скрыть элемент
                if(self.orders.length === 1){//Если удаляется последний товар
                  self.show = false;
                  self.showCatalogRef = true;
                }
                var selfBase = self.orders[index].basePriceForOne,
                    selfTotal = self.orders[index].totalPriceForOne;
                self.orders[index].basePriceForOne = 0;//обнулить стоимость (чтобы сразу обновить общую стоимость)
                self.orders[index].totalPriceForOne = 0;
                $.ajax({
                    type: "get",
                    url: "/ajax/index.php",
                    data: {"ELEMENT_ID": id, "QUANTITY": 0, "action": "QUANTITY"},
                    success: function(response){
                      var data = JSON.parse(response);  
                      if(data.result === "success"){
                          self.orders.splice(index, 1);
                          if(self.orders.length === 0){//остались ли ещё товары
                              self.show = false;
                              self.showCatalogRef = true;
                          }
                      }else{
                          self.orders[index].visible = true;//вернуть элемент
                          self.orders[index].basePriceForOne = selfBase;
                          self.orders[index].totalPriceForOne = selfTotal;
                          alert("Не удалось удалить товар из корзины");
                      }
                    },
                    error: function(){
                        self.orders[index].visible = true;
                        self.orders[index].basePriceForOne = selfBase;
                        self.orders[index].totalPriceForOne = selfTotal;
                        alert("Не удалось удалить товар из корзины");
                    },
                    complete: function(){
                        self.calcDelivery();//пересчет суммы доставки
                    }
                });
            },
            favoriteToggle: function (id, fav) {
                var self = this,
                    index = self.orders.map(function(v) {return v.id}).indexOf(id);
                self.orders[index].favorite = fav;
                var dataAjax;
                if(self.orders[index].favorite){
                    dataAjax = {"ID": self.orders[index].productID, action: "FAVOURITE_ADD"};
                }else{
                    dataAjax = {"ID": self.orders[index].productID, action: "FAVOURITE_REMOVE"};
                }
                $.ajax({
                    type: "get",
                    url: "/ajax/index.php",
                    data: dataAjax,
                    success: function(response){
                        var data = JSON.parse(response);
                        if(data.result === "success"){
                            $('.b-fav-number').text(data.COUNT);
                            if (data.COUNT == 0) {
                                $('.b-fav-round').addClass('hide');
                            } else {
                                $('.b-fav-round').removeClass('hide');
                            }
                        }else{
                            self.orders[index].favorite = !self.orders[index].favorite;
                            alert(data.error);
                        }
                    },
                    error: function(){}
                });
            },
            updateOrder: function (orders) {
                this.orders = [].concat(orders);
            },
            updateCoupons: function (coupons) {
                this.couponList = coupons;
            },
            validationForm: function () {
                if(this.formValid){
                    this.form.nowSubmit = true;
                    document.getElementById('b-order-form').submit();
                }
            },
            openMap: function (event) {
                $(".b-popup-map-link").click();
                console.log(this.form.address);
                // console.log(event.target);
                // event.target.blur();
            },
            calcDelivery: function () {
                var active = this.form.deliveryActive,
                    index = this.form.deliveryList.map(function(v) {return v.id}).indexOf(active),
                    zip = $('#postal-code').val();
                var self = this;

                if( active == "15" ){
                    if( typeof IPOLSDEK_pvz == "object" ){
                        IPOLSDEK_pvz.setPrices();
                    }
                }

                if($('#postal-code-vue').val() && !this.form.deliveryList[index].fixedCost){
                    //заблочить кнопку
                    this.form.blockedSubmit = true;
                    $.ajax({
                        type: "get",
                        url: "/ajax/index.php",
                        data: {"delivery_id": this.form.deliveryList[index].id, "zip": zip ,"action": "DELIVERY"},
                        success: function(response){
                            var data = JSON.parse(response);
                            if(data.result === "success"){
                                $('#delivery-cost').val(data.cost);
                                var e = new Event("change");
                                $('#delivery-cost')[0].dispatchEvent(e);
                            }else{
                                alert(data.error);
                            }
                        },
                        error: function(){},
                        complete: function(){
                            //вернуть кнопку
                            self.form.blockedSubmit = false;
                        },
                    });
                }else{
                    $('#delivery-cost').val(this.form.deliveryList[index].cost);
                    var e = new Event("change");
                    $('#delivery-cost')[0].dispatchEvent(e);
                }
            },
            changeCost: function () {
                //console.log("changeCost = " + $('#delivery-cost').val());
                var active = this.form.deliveryActive,
                    index = this.form.deliveryList.map(function(v) {return v.id}).indexOf(active);
                this.form.deliveryList[index].cost = parseFloat($('#delivery-cost').val());
            },
            removeWarning: function (id) {
                index = this.orders.map(function(v) {return v.id}).indexOf(id);
                this.orders[index].limitWarning = false;
            }
        },
        computed: {
            rawBase: function () {
                var res = 0;
                this.orders.forEach(function(item, i, arr) {
                    res += item.basePriceForOne * item.quantity;
                });
                return +res.toFixed(1);
            },
            rawTotal: function () {
                var res = 0;
                this.orders.forEach(function(item, i, arr) {
                    res += item.totalPriceForOne * item.quantity;
                });
                return +res.toFixed(1);
            },
            discount: function () {
                var res = this.rawBase - this.rawTotal;
                return (res > 0) ? +res.toFixed(1) : 0;
            },
            delivery: function () {
                var active = this.form.deliveryActive;
                return this.form.deliveryList.filter(function(v) {return v.id === active})[0].cost;
            },
            total: function () {
                return +((this.rawTotal + this.delivery).toFixed(1));
            },
            formValid: function() {
                this.$validator.validate();
                var self = this;
                return Object.keys(this.fields).every( function(field) {
                    return self.fields[field] && self.fields[field].valid;
                });
                // var self = this,
                //     valid = true;
                // for (var field in this.fields){
                //     if(self.fields[field] && self.fields[field].valid){
                //         $("#app-order input[name='"+field+"']").removeClass("error");
                //     }else{
                //         $("#app-order input[name='"+field+"']").addClass("error");
                //         valid = false;
                //     }
                // }
                // return valid;
            },
        },
        components: {
            //Список позиций заказа
            'v-order-list': {
                props: ['orders', 'isAuth'],
                data: function () {
                    return {

                    }
                },
                template: '\
                <div class="b-order-list">\
                    <v-order-item\
                        @onRemoveItem="removeItem"\
                        @onChangeQuantity="changeQuantity"\
                        @onFavoriteToggle="favoriteToggle"\
                        @onRemoveWarning="removeWarning"\
                        :_id="order.id"\
                        :_image="order.image"\
                        :_name="order.name"\
                        :_productName="order.productName"\
                        :_url="order.url"\
                        :_quantity="order.quantity"\
                        :_basePriceForOne="order.basePriceForOne"\
                        :_totalPriceForOne="order.totalPriceForOne"\
                        :_maxCount="order.maxCount"\
                        :_limitWarning="order.limitWarning"\
                        :_favorite="order.favorite"\
                        :_visible="order.visible"\
                        :_isAuth="isAuth"\
                        :key="order.id" v-for="order in orders">\
                    </v-order-item>\
                </div>',
                methods: {
                    removeItem: function (id) {
                        this.$emit('onRemoveItem', id);
                    },
                    changeQuantity: function (id, quantity) {
                        this.$emit('onChangeQuantity', id, quantity);
                    },
                    favoriteToggle: function (id, fav) {
                        this.$emit('onFavoriteToggle', id, fav);
                    },
                    removeWarning: function (id) {
                        this.$emit('onRemoveWarning', id);
                    }
                },
                components: {
                    //Позиция заказа
                    'v-order-item': {
                        props:{
                            _id: [String, Number],
                            _image: String,
                            _name: String,
                            _productName: String,
                            _url: String,
                            _quantity: Number,
                            _basePriceForOne: Number,
                            _totalPriceForOne: Number,
                            _maxCount: Number,
                            _limitWarning: Boolean,
                            _favorite: Boolean,
                            _visible: Boolean,
                            _isAuth: Boolean
                        },
                        data: function () {
                            return {
                                //visible: true,
                                //favorite: false
                            }
                        },
                        computed: {
                            id: function () {
                               return this._id;
                            },
                            image: function () {
                               return this._image;
                            },
                            name: function () {
                               return this._name;
                            },
                            productName: function () {
                               return this._productName;
                            },
                            url: function () {
                               return this._url;
                            },
                            quantity: {
                                get: function () {
                                    return this._quantity;
                                },
                                set: function (value) {
                                    if(isNumeric(value)){
                                        if(value > 0 && value <= this.maxCount){
                                           this.onChangeQuantity(this.id, value); 
                                        }
                                        value = (value < 1) ? 1 : value;
                                        value = (value > this.maxCount) ? this.maxCount : value;
                                        console.log(value);
                                    }
                                    //value = value.replace(/\D+/g,"");
                                }
                            },
                            basePrice: function () {
                               return +(this._basePriceForOne * this.quantity).toFixed(1);
                            },
                            totalPrice: function () {
                               return +(this._totalPriceForOne * this.quantity).toFixed(1);
                            },
                            maxCount: function () {
                               return this._maxCount;
                            },
                            limitWarning: function () {
                                return this._limitWarning;
                            },
                            favorite: function () {
                                return this._favorite;
                            },
                            visible: function () {
                                return this._visible;
                            },
                            isAuth: function () {
                                return this._isAuth;
                            }
                        },
                        template: '\
                        <div class="b-order-item-cont" v-show="visible">\
                            <div class="b-order-item">\
                                <a :href="url" class="item-field b-order-item-img">\
                                    <img :src="image">\
                                </a>\
                                <a :href="url" class="item-field b-order-item-name">\
                                    <p v-if="productName">\
                                        {{ productName }} <span class=\'b-order-item-name-offer\'>{{ name }}</span>\
                                    </p>\
                                    <p v-else>{{ name }}</p>\
                                </a>\
                                <div class="item-field b-order-item-quantity">\
                                    <div class="product-quantity">\
                                        <a href="#" @click.prevent="quantityReduce" class="icon-minus quantity-reduce"></a>\
                                        <input v-model.number="quantity" type="text" name="quantity" class="quantity-input" maxlength="3">\
                                        <a href="#" @click.prevent="quantityAdd" class="icon-plus quantity-add"></a>\
                                    </div>\
                                </div>\
                                <div class="item-field b-order-item-price" :class="{ \'has-discount\': basePrice != totalPrice }">\
                                    <div v-show="basePrice != totalPrice" class="price-base">{{ formatNumber(basePrice) }}<span class="icon-ruble"></span></div>\
                                    <div class="price-total">{{ formatNumber(totalPrice) }}<span class="icon-ruble"></span></div>\
                                </div>\
                                <div class="item-field b-order-item-controls">\
                                    <div \
                                        @click.prevent="onFavoriteToggle" \
                                        :class="{active: favorite}" \
                                        class="control-favorite"\
                                        v-show="isAuth"\
                                    >\
                                        <div class="icon-star-order"></div>\
                                        <div class="icon-star-order-fill"></div>\
                                    </div>\
                                    <a href="#" \
                                        @click.prevent="onRemoveItem" \
                                        class="control-delete icon-close"\
                                    ></a>\
                                </div>\
                            </div>\
                            <div v-if="limitWarning" class="quantity-warning">\
                                <span>Извините, но указанное ранее количество товара недоступно. Установлено ближайшее доступное значение.</span>\
                                <a href="#" \
                                    @click.prevent="onRemoveWarning"\
                                    class="icon-close"\
                                ></a>\
                            </div>\
                        </div>',
                        methods: {
                            quantityReduce: function () {
                                this.quantity--;
                            },
                            quantityAdd: function () {
                                this.quantity++;
                            },
                            onFavoriteToggle: function () {
                                this.$emit('onFavoriteToggle', this.id, !this.favorite);
                            },
                            onRemoveItem: function () {
                                this.$emit('onRemoveItem', this.id);
                            },
                            onChangeQuantity: function (id, value) {
                                this.onRemoveWarning();
                                this.$emit('onChangeQuantity', id, value);
                            },
                            onRemoveWarning: function () {
                                this.$emit('onRemoveWarning', this.id);
                            },
                            formatNumber: function (number) {
                                return formatNumberExternal(number);
                            }
                        }
                    }
                }
            },
            //блок с итоговой ценой
            'v-totals': {
                props: {
                    _rawBase: Number,
                    _rawTotal: Number,
                    _discount: Number,
                    _delivery: Number,
                    _total: Number,
                    _couponList: Array,
                },
                data: function () {
                    return {
                        coupon: "",
                        validInput: true,
                        ajaxCoupon: false,
                        couponList: this._couponList,
                    }
                },
                template: '\
                  <div class="b-order-totals">\
                    <div class="b-price-string b-price-raw clearfix">\
                      <span class="explanation">Стоимость заказа:</span>\
                      <div class="b-price-total" :class="{ \'has-discount\': _rawBase != _rawTotal }">\
                        <div v-show="_rawBase != _rawTotal" class="price-base">{{ formatNumber(_rawBase) }}<span class="icon-ruble"></span></div>\
                        <div class="price-total">{{ formatNumber(_rawTotal) }}<span class="icon-ruble"></span></div>\
                      </div>\
                    </div>\
                    <div class="b-order-coupon">\
                      <div class="b-input">\
                        <p>Купон</p>\
                        <input\
                            @keyup.enter="sendCoupon"\
                            @change="sendCoupon"\
                            @input="validInput = true"\
                            v-model="coupon"\
                            :class="{error: !validInput}"\
                            type="text"\
                            name="coupon"\
                            placeholder="HFJDY61HQ"\
                        >\
                      </div>\
                      <a href="#" class="b-btn" @click.prevent="sendCoupon">Применить</a>\
                      <div class="coupon-list">\
                        <div class="coupon-item"\
                            v-for="coupon in couponList"\
                            :key="coupon.id"\
                            :class="{\'coupon-success\': coupon.success, \'coupon-error\': !coupon.success}"\
                            v-show="coupon.visible"\
                        >\
                            <p><b>{{ coupon.name }}</b> - {{ (coupon.success) ? "купон применён" : "купон не найден" }}</p>\
                            <a href="#" class="dashed" @click.prevent="removeCoupon(coupon.id, coupon.name)">Удалить</a>\
                        </div>\
                      </div>\
                    </div>\
                    <div v-show="_discount > 0" class="b-price-string clearfix">\
                      <span class="explanation">Размер скидки:</span>\
                      <div class="price-total">{{ formatNumber(_discount) }}<span class="icon-ruble"></span></div>\
                    </div>\
                    <div class="b-price-string clearfix">\
                      <span class="explanation">Стоимость доставки:</span>\
                      <div class="price-total">{{ formatNumber(_delivery) }}<span class="icon-ruble"></span></div>\
                    </div>\
                    <div class="b-price-string clearfix price-final">\
                      <span class="explanation">Итого:</span>\
                      <div class="price-total">{{ formatNumber(_total) }}<span class="icon-ruble"></span></div>\
                    </div>\
                  </div>\
                ',
                methods: {
                    updateOrder: function (orders) {
                        this.$emit('onUpdateOrder', orders);
                    },
                    updateCoupons: function (coupons) {
                        this.$emit('onUpdateCoupons', coupons);
                    },
                    sendCoupon: function () {
                        var self = this;
                        if(self.coupon && !self.ajaxCoupon){
                            self.ajaxCoupon = true;
                            $.ajax({
                                type: "get",
                                url: "/ajax/index.php",
                                data: {"COUPON_NAME": self.coupon, "action": "COUPON_ACTION"},
                                success: function(response){
                                    var data = JSON.parse(response);
                                    if(data.result === "success"){
                                        self.couponList = [].concat(data.coupons);
                                        self.updateOrder(data.items);
                                    }else{
                                        alert(data.error);
                                    }
                                },
                                error: function(){},
                                complete: function(){
                                    self.coupon = "";
                                    self.ajaxCoupon = false;
                                },
                            });
                        }else{
                            if(!self.coupon){
                                self.validInput = false;
                            }
                           
                        }
                    },
                    removeCoupon: function (id, name) {
                        var self = this,
                            index = self.couponList.map(function(v) {return v.id}).indexOf(id);
                        self.couponList[index].visible = false;//скрыть элемент
                        $.ajax({
                            type: "get",
                            url: "/ajax/index.php",
                            data: {"COUPON_NAME": name, "action": "COUPON_ACTION", "COUPON_DELETE": "Y"},
                            success: function(response){
                                var data = JSON.parse(response);
                                if(data.result === "success"){
                                    self.couponList = [].concat(data.coupons);
                                    self.updateOrder(data.items);
                                }else{
                                    self.couponList[index].visible = true;
                                    alert(data.error);
                                }
                            },
                            error: function(){
                                self.couponList[index].visible = true;
                            }
                        });
                    },
                    formatNumber: function (number) {
                        return formatNumberExternal(number);
                    }
                },
                mounted: function () {
                    
                }
            }
        },
        updated: function () {
          this.$nextTick(function () {
            if($('#app-order input[name="phone"]').length && !this.pluginsInit){
                $('#app-order input[name="phone"]').mask('+7 (000) 000-00-00');
                if( typeof autosize == "function" ){
                    autosize(document.querySelectorAll('#app-order textarea[name="addr"], #app-order textarea[name="comment"]'));
                }
                window.onresize = windowResize;
                windowResize();
                this.pluginsInit = true;
            }
            if($(".b-order-sdek-map").length && $(".b-cdek-map-cont").length && !this.sdekInit){
                $(".b-order-sdek-map").append($(".b-cdek-map"));
                this.sdekInit = true;
            }
          })
        }
    });

    var app = new Vue({
        el: '#app-order',
        data: {

        },
        mounted: function () {
            
        },
    });

}());
