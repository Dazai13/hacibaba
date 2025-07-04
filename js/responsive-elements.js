function initResponsiveElements() {
    function updateLayout() {
        var screenWidth = window.innerWidth;
        $('[data-device]').hide();
        
        if (screenWidth < 768) {
            $('[data-device="mobile"]').show();
        } 
        else if (screenWidth < 1024) {
            $('[data-device="tablet"]').show();
        } 
        else {
            $('[data-device="desktop"]').show();
        }
    }

    updateLayout();
    $(window).on('resize', debounce(updateLayout, 300));
}