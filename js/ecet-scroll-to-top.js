// cache le bouton retour vers le haut en position haute < 500
// apparition du bouton retour vers le haut avec un fading pour une position > 500
// permet un défilement fluide vers le haut

jQuery(document).ready(function($){
	
    $(window).scroll(function(){
        if ($(this).scrollTop() < 500) {
            $('.scroll-top') .fadeOut();
        } else {
            $('.scroll-top') .fadeIn();
        }
    });
	
    $('.scroll-top').on('click', function(){
        $('html, body').animate({scrollTop:0}, 'fast');
        return false;
    });
	
});