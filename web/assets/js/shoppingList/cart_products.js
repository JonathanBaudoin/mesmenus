function CartProducts() {

}

CartProducts.prototype = {
    ready: function() {
        CartProducts.prototype.productOnClick();
    },

    productOnClick: function() {
        $('.ingredient').click(function (e) {
            e.preventDefault();

            var product = $(this);

            $.ajax({
                url: Routing.generate('app_shoppinglist_add_product_to_cart', { id: $('#shopping-list').data('menu'), productId: product.data('id') }),
                success: function(msg) {
                    if (msg !== 'error') {
                        if (msg === 'removed') {
                            var classToAdd    = 'error';
                            var classToRemove = 'success';
                        } else if (msg === 'added') {
                            var classToAdd    = 'success';
                            var classToRemove = 'error';
                        }

                        product.removeClass('flash-'+classToRemove);
                        product.addClass('flash-'+classToAdd);
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