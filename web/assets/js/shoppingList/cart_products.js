function CartProducts() {

}

CartProducts.prototype = {
    ready: function() {
        CartProducts.prototype.productOnClick();
    },

    productOnClick: function() {
        $('.ingredient').click(function (e) {
            e.preventDefault();

            var ingredient = $(this);

            $.ajax({
                url: Routing.generate('app_shoppinglist_add_product_to_cart', { id: $('#shopping-list').data('menu'), productId: ingredient.data('id') }),
                success: function(msg) {
                    if (msg !== 'error') {
                        if (msg === 'removed') {
                            var classToAdd    = 'removed';
                            var classToRemove = 'added';
                        } else if (msg === 'added') {
                            var classToAdd    = 'added';
                            var classToRemove = 'removed';
                        }

                        ingredient.removeClass(classToRemove);
                        ingredient.addClass(classToAdd);
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