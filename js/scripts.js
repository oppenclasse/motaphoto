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

// page acceuil 

jQuery(document).ready(function($) {
    var page = 1;

    function loadPhotos() {
        var category = $('#categorie-filter').val();
        var format = $('#format-filter').val();
        var order = $('#date-sort').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'load_photos',
                category: category,
                format: format,
                order: order,
                page: page
            },
            success: function(response) {
                if (page === 1) {
                    $('#photo-list').html(response);
                } else {
                    $('#photo-list').append(response);
                }
            }
        });
    }

    $('#category-filter, #format-filter, #date-sort').change(function() {
        page = 1;
        loadPhotos();
    });

    $('#load-more').click(function() {
        page++;
        loadPhotos();
    });

    loadPhotos();
});
