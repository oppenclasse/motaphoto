
// Code pour la gestion des aperçus de miniatures dans les liens de navigation
jQuery(document).ready(function($) {

    // Affiche la miniature au survol du lien de navigation
    $('.navigation-link').hover(function() {
        var img = $(this).data('thumbnail');
        console.log($(this).data('thumbnail'))
        $('.thumbnail-preview img').attr('scr', img);
    });
});

// Code pour la gestion de la page d'accueil (chargement de photos avec filtres et pagination)
