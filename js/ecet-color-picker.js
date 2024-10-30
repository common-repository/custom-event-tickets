//Le document.ready est la partie obligatoire de jQuery pour ne lancer le script 
//qu’une fois la page chargée et opérationnelle, et enfin vous invoquez le color picker 
//sur tout champ avec la classe .color-field 
jQuery(document).ready(function($){
    $('.color-field').wpColorPicker();
});