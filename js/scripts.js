// document.addEventListener('DOMContentLoaded', function() {
//     var modal = document.getElementById('contact-modal');
//     var btn = document.getElementById('contact-btn');
//     var span = document.getElementsByClassName('close')[0];

//     btn.onclick = function() {
//         modal.style.display = 'block';
//     }

//     span.onclick = function() {
//         modal.style.display = 'none';
//     }

//     window.onclick = function(event) {
//         if (event.target == modal) {
//             modal.style.display = 'none';
//         }
//     }
// });

jQuery(document).ready(function($) {
    $('.navigation-link').each(function() {
        var img = new Image();
        img.src = $(this).data('thumbnail');
    });

    $('.navigation-link').hover(function() {
        $(this).find('.thumbnail-preview').show();
    }, function() {
        $(this).find('.thumbnail-preview').hide();
    });
});
