jQuery(document).ready(function($) {
    // Показать popup через 3 секунды
    setTimeout(function() {
        $('#custom-popup').fadeIn();
    }, 3000);

    // Закрыть popup при клике на крестик
    $('.close').click(function() {
        $('#custom-popup').fadeOut();
    });
});