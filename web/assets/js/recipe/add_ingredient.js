$('#recipe_ingredients').on('change', function() {

    var selectedIngredients = $('#recipe_ingredients option:selected');

    selectedIngredients.each(function() {
        console.log(this);
        $('#list-ingredients').append('<div><select id="'+$(this).val()+'" name="recipe[ingredients][]"><option value="test">'+$(this).text()+'</option></select></div>')

    });




});
