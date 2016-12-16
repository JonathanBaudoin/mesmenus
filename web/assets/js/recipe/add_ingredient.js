function AddIngredient() {

}

AddIngredient.prototype = {
    ready: function() {
        AddIngredient.prototype.displayIngredientOnLoad();
        AddIngredient.prototype.displayIngredientOnChange();
    },

    displayIngredientOnLoad: function() {
        var selectedIngredients = $('.recipe-has-ingredient');
        selectedIngredients.each(function() {
            console.log($(this).data('ingredient-name'));
            $('#recipe_ingredients option[value='+$(this).data('ingredient-id')+']').prop('selected', 'selected');
        });
    },

    displayIngredientOnChange: function() {
        $('#recipe_ingredients').on('change', function() {

            var selectedIngredients = $('#recipe_ingredients option:selected');
            selectedIngredients.each(function() {

                var ingredientId = 'ingredient_'+$(this).val();

                // If ingredient doesn't exist
                if ($('#'+ingredientId).length == 0) {
                    $('#added-ingredients').append(
                        '<div id="'+ingredientId+'" class="recipe-has-ingredient" data-ingredient-id="'+$(this).val()+'"  data-ingredient-name="'+$(this).text()+'">' +
                        '<input class="name" name="ingredient['+$(this).val()+'][name]" value="'+$(this).text()+'" disabled />' +
                        '<input type="number" class="amount" name="ingredient['+$(this).val()+'][amount]" placeholder="Quantité" />' +
                        '<input class="measure-unit" name="ingredient['+$(this).val()+'][measureUnit]"  placeholder="Unité de mesure" />' +
                        '</div>'
                    );
                }
            });

            var addedIngredients = $('#added-ingredients div');
            addedIngredients.each(function() {
                var addedIngredientId = $(this).data('ingredient-id');

                // If an added ingredient is not selected anymore
                if (!$('#recipe_ingredients option[value="'+addedIngredientId+'"]').is(':selected')) {
                    $('#ingredient_'+addedIngredientId).remove();
                }
            });
        });
    }
};

var initAddIngredient = new AddIngredient();
$(function() {
    initAddIngredient.ready();
});