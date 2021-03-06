function AddIngredient() {

}

AddIngredient.prototype = {
    ready: function() {
        AddIngredient.prototype.createNewIngredient();
        AddIngredient.prototype.displayIngredientOnLoad();
        AddIngredient.prototype.displayIngredientOnChange();
    },

    createNewIngredient: function() {
        $('#add-ingredient #ingredient-button .btn').click(function(e) {
            e.preventDefault();

            var ingredientForm = $('#add-ingredient #ingredient-form');
            ingredientForm.show();
            $(this).hide();

            var contentMessage = $('#add-ingredient-message');
            contentMessage.html('').removeClass();

            $('#add-ingredient #ingredient-form .btn').click(function(e) {
                e.preventDefault();

                var ingredientName = $('#add-ingredient #ingredient-form #ingredient_name').val();
                contentMessage.removeClass();

                console.log('toto');

                $.ajax({
                    url: $('#add-ingredient #ingredient-form #ingredient_form').attr('action'),
                    data: {
                        'ingredientName': ingredientName
                    },
                    success: function(data) {
                        contentMessage.addClass('flash-message flash-'+data.return);
                        contentMessage.html(data.message);

                        if (data.return === 'success') {
                            $('#recipe_ingredients').append('<option value="'+data.ingredientId+'" selected="selected">'+data.ingredientName+'</option>');

                            $('#added-ingredients').append(
                                '<div id="ingredient_'+data.ingredientId+'" class="recipe-has-ingredient" data-ingredient-id="'+data.ingredientId+'"  data-ingredient-name="'+data.ingredientName+'">' +
                                '<input class="name" name="ingredient['+data.ingredientId+'][name]" value="'+data.ingredientName+'" disabled />' +
                                '<input type="number" required="required" class="amount" name="ingredient['+data.ingredientId+'][amount]" placeholder="Quantité" />' +
                                '<input class="measure-unit" name="ingredient['+data.ingredientId+'][measureUnit]"  placeholder="Unité de mesure" />' +
                                '</div>'
                            );

                            // ingredientForm.hide('slow');
                            // $('#add-ingredient #ingredient-button .btn').show();
                            $('#add-ingredient #ingredient-form #ingredient_name').val('');
                        }
                    }
                })
            });
        });
    },

    displayIngredientOnLoad: function() {
        var selectedIngredients = $('.recipe-has-ingredient');
        selectedIngredients.each(function() {
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
                        '<input type="number" required="required" class="amount" name="ingredient['+$(this).val()+'][amount]" placeholder="Quantité" />' +
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