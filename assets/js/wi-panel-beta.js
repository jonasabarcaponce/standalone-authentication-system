$(document).ready(function() {
    $('.wi-menu-toggle').click(function(){
        var getToggleStatus = $(this).attr('data-value');
        switch(getToggleStatus) {
            case 'wi-toggle-closed' : 
                $('.wi-p-navigation').addClass('collapsed');
                $(this).attr('data-value','wi-toggle-open');
                $(this).html('<i class="fas fa-times"></i>');
            break; 
            case 'wi-toggle-open' : 
                $('.wi-p-navigation').removeClass('collapsed');
                $(this).attr('data-value','wi-toggle-closed');
                $(this).html('<i class="fas fa-bars"></i>');
            break; 
        }
    }); 
    $(function () {
        $('[data-toggle="popover"]').popover();
    });   
    setTimeout(function() {
        $('.wi-loader-main').fadeOut(300);
    },2000);
    setTimeout(function() {
        $('.alert').slideUp(300);
    },4000);
});