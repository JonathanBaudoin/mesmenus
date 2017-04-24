function CartProducts() {

}

CartProducts.prototype = {
    ready: function() {
        CartProducts.prototype.addProduct();
    },

    addProduct: function() {
        $('.product').click(function (e) {
            e.preventDefault();

            var product = $(this);

            $.ajax({
                url: Routing.generate('app_shoppinglist_add_product_to_cart', { id: $('.shopping-list').data('menu'), productId: product.data('id') }),
                success: function(msg) {
                    if (msg !== 'error') {
                        var productNotInCart = $('#not-in-cart').find("[data-id='" + product.data('id') + "']");
                        var productInCart    = $('#in-cart').find("[data-id='" + product.data('id') + "']");

                        if (msg === 'removed') {
                            productInCart.hide('fast');
                            productNotInCart.show('fast');
                        } else if (msg === 'added') {
                            productInCart.show('fast');
                            productNotInCart.hide('fast');
                        }
                    }
                }
            });
        });
    }
};

var initCartProducts = new CartProducts();
$(function() {
    initCartProducts.ready();
});