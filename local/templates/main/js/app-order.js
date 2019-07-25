(function() {

    var myWidth;

    function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
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
            $(".b-side-right").trigger("sticky_kit:detach");
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
                },
                delayQuantity: 300,
                timeoutQuantity: null,
                countQueue: 0,
                pluginsInit: false,
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
                this.form.deliveryActive = this.form.deliveryList[0].value;
                //обнулить стоимость доставок
                for (i = 0; i < this.form.deliveryList.length; i++) {
                    this.form.deliveryList[i].cost = 0;
                }
            }
            if(dataOrder.payments){
                this.form.paymentList = dataOrder.payments;
                this.form.paymentActive = this.form.paymentList[0].value;
            }
            if(dataOrder.isAuth){
                this.isAuth = dataOrder.isAuth;
            }
          }
          if(this.orders.length === 0){
              this.show = false;
              this.showCatalogRef = true;
          }
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
                                    v-validate="{ required: true, regex: /^\\+\\d \\(\\d{3}\\) \\d{3} \\d{4}$/ }"\
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
                                        :checked="form.deliveryActive == delivery.value"\
                                        v-model="form.deliveryActive"\
                                        :value="delivery.value"\
                                        @change="calcDelivery"\
                                    >\
                                    <label :for="getLabel(\'delivery\', delivery.id)">{{ delivery.name }}</label>\
                                </li>\
                            </ul>\
                            <ul class="b-delivery-tabs">\
                                <li v-for="delivery in form.deliveryList" :key="delivery.id" v-show="form.deliveryActive == delivery.value">\
                                    {{delivery.text}}\
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
                                        :checked="form.paymentActive == payment.value"\
                                        v-model="form.paymentActive"\
                                        :value="payment.value"\
                                    >\
                                    <label :for="getLabel(\'payment\', payment.id)">{{ payment.name }}</label>\
                                </li>\
                            </ul>\
                          </div>\
                        </div>\
                        <div class="b-order-form-bottom">\
                            <div class="b-order-sdek-map" v-if="form.deliveryActive == \'delivery-15\'">\
                                <p>Карта для СДЭКа (класс .b-order-sdek-map)</p>\
                            </div>\
                            <div class="b-order-address-input" v-else>\
                                <div v-if="form.deliveryActive != \'delivery-5\'">\
                                    <div class="b-textarea">\
                                        <p>Адрес доставки</p>\
                                        <textarea rows="1" name="address" placeholder="Введите адрес" v-model="form.address"\
                                            v-validate="\'required\'"\
                                            :class="{ error: errors.first(\'address\')}"\
                                            @click="openMap"\
                                        ></textarea>\
                                    </div>\
                                </div>\
                            </div>\
                            <input id="postal-code-vue" type="hidden" name="postal-code-vue" @change="calcDelivery">\
                            <input id="delivery-cost" type="text" name="delivery-cost" @change="changeCost">\
                            <div class="b-textarea">\
                                <p>Комментарий к заказу</p>\
                                <textarea rows="1" name="comment" placeholder="Введите комментарий" v-model="form.comment"></textarea>\
                            </div>\
                        </div>\
                    </form>\
                    <a href="#" class="b-btn" @click.prevent="validationForm">Оформить заказ</a>\
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
            </div>\
            \
            <div v-if="showCatalogRef">\
              <span>Ваша корзина пуста. </span><a class="dashed" href="/catalog/">Перейти в каталог</a>\
            </div>\
            \
            <div v-if="showPreloader" class="b-order-preloader">\
              <img src="i/preloader.svg">\
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
                        }
                    });
                }, self.delayQuantity);
            },
            removeItem: function (id) {
                var self = this,
                    index = self.orders.map(function(v) {return v.id}).indexOf(id);
                self.orders[index].visible = false;//скрыть элемент
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
                        if(data.result === "error"){
                            self.orders[index].favorite = !self.orders[index].favorite;
                        }
                    },
                    error: function(){}
                });
            },
            // addCoupon: function (coupon) {
            //     this.couponList.push(coupon);
            // },
            // removeCoupon: function (index) {
            //     this.couponList.splice(index, 1);
            // },
            updateOrder: function (orders) {
                this.orders = [].concat(orders);
            },
            updateCoupons: function (coupons) {
                this.couponList = coupons;
            },
            validationForm: function () {
                if(this.formValid){
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
                    index = this.form.deliveryList.map(function(v) {return v.value}).indexOf(active),
                    zip = $('#postal-code').val();
                var self = this;
                if($('#postal-code-vue').val()){
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
                    });
                }
            },
            changeCost: function () {
                //console.log("changeCost = " + $('#delivery-cost').val());
                var active = this.form.deliveryActive,
                    index = this.form.deliveryList.map(function(v) {return v.value}).indexOf(active);
                this.form.deliveryList[index].cost = parseFloat($('#delivery-cost').val());
            }
        },
        computed: {
            rawBase: function () {
                var res = 0;
                this.orders.forEach(function(item, i, arr) {
                    res += item.basePriceForOne * item.quantity;
                });
                return +res.toFixed(2);
            },
            rawTotal: function () {
                var res = 0;
                this.orders.forEach(function(item, i, arr) {
                    res += item.totalPriceForOne * item.quantity;
                });
                return +res.toFixed(2);
            },
            discount: function () {
                var res = this.rawBase - this.rawTotal;
                return (res > 0) ? +res.toFixed(2) : 0;
            },
            delivery: function () {
                var active = this.form.deliveryActive;
                return this.form.deliveryList.filter(function(v) {return v.value === active})[0].cost;
            },
            total: function () {
                return +((this.rawTotal + this.delivery).toFixed(2));
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
                        :_id="order.id"\
                        :_image="order.image"\
                        :_name="order.name"\
                        :_url="order.url"\
                        :_quantity="order.quantity"\
                        :_basePriceForOne="order.basePriceForOne"\
                        :_totalPriceForOne="order.totalPriceForOne"\
                        :_maxCount="order.maxCount"\
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
                },
                components: {
                    //Позиция заказа
                    'v-order-item': {
                        props:{
                            _id: [String, Number],
                            _image: String,
                            _name: String,
                            _url: String,
                            _quantity: Number,
                            _basePriceForOne: Number,
                            _totalPriceForOne: Number,
                            _maxCount: Number,
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
                               return +(this._basePriceForOne * this.quantity).toFixed(2);
                            },
                            totalPrice: function () {
                               return +(this._totalPriceForOne * this.quantity).toFixed(2);
                            },
                            maxCount: function () {
                               return this._maxCount;
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
                        <div class="b-order-item" v-show="visible">\
                            <a :href="url" class="item-field b-order-item-img">\
                                <img :src="image">\
                            </a>\
                            <a :href="url" class="item-field b-order-item-name">\
                                <p>{{ name }}</p>\
                            </a>\
                            <div class="item-field b-order-item-quantity">\
                                <div class="product-quantity">\
                                    <a href="#" @click.prevent="quantityReduce" class="icon-minus quantity-reduce"></a>\
                                    <input v-model.number="quantity" type="text" name="quantity" class="quantity-input" maxlength="3">\
                                    <a href="#" @click.prevent="quantityAdd" class="icon-plus quantity-add"></a>\
                                </div>\
                            </div>\
                            <div class="item-field b-order-item-price has-discount">\
                                <div v-show="basePrice != totalPrice" class="price-base">{{ basePrice }}<span class="icon-ruble"></span></div>\
                                <div class="price-total">{{ totalPrice }}<span class="icon-ruble"></span></div>\
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
                                this.$emit('onChangeQuantity', id, value);
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
                      <div class="b-price-total has-discount">\
                        <div v-show="_rawBase != _rawTotal" class="price-base">{{ _rawBase }}<span class="icon-ruble"></span></div>\
                        <div class="price-total">{{ _rawTotal }}<span class="icon-ruble"></span></div>\
                      </div>\
                    </div>\
                    <div class="b-order-coupon">\
                      <div class="b-input">\
                        <p>Купон</p>\
                        <input\
                            @keyup.enter="sendCoupon"\
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
                      <div class="price-total">{{ _discount }}<span class="icon-ruble"></span></div>\
                    </div>\
                    <div class="b-price-string clearfix">\
                      <span class="explanation">Стоимость доставки:</span>\
                      <div class="price-total">{{ _delivery }}<span class="icon-ruble"></span></div>\
                    </div>\
                    <div class="b-price-string clearfix price-final">\
                      <span class="explanation">Итого:</span>\
                      <div class="price-total">{{ _total }}<span class="icon-ruble"></span></div>\
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

                                    }
                                },
                                error: function(){},
                                complete: function(){
                                    self.coupon = "";
                                    self.ajaxCoupon = false;
                                },
                            });
                        }else{
                            self.validInput = false;
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
                                }
                            },
                            error: function(){
                                self.couponList[index].visible = true;
                            }
                        });
                    },
                },
                mounted: function () {
                    
                }
            }
        },
        updated: function () {
          this.$nextTick(function () {
            if($('#app-order input[name="phone"]').length && !this.pluginsInit){
                $('#app-order input[name="phone"]').mask('+7 (000) 000 0000');
                if( typeof autosize == "function" ){
                    autosize(document.querySelectorAll('#app-order textarea[name="address"], #app-order textarea[name="comment"]'));
                }
                window.onresize = windowResize;
                windowResize();
                this.pluginsInit = true;
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
