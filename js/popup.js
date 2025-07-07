jQuery(document).ready(function($) {
    $('a.card__btn').on('click', function(e) {
        e.preventDefault();
        var addToCartUrl = $(this).attr('href');
        $('#custom-popup').fadeIn();
        
        $.ajax({
            url: addToCartUrl,
            method: 'GET',
            success: function(response) {
                console.log('Товар добавлен в корзину');

            },
            error: function() {
                console.error('Ошибка добавления в корзину');
            }
        });
    });
    $('#close-popup').click(function() {
        $('#custom-popup').fadeOut();
    });
    $(document).mouseup(function(e) {
        if (!$('.popup-content').is(e.target) && $('.popup-content').has(e.target).length === 0) {
            $('#custom-popup').fadeOut();
        }
    });
});