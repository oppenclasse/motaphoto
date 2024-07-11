<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="custom-homepage-content">
            <?php
            while (have_posts()) :
                the_post();
                the_content(); // Affiche le contenu de la page WordPress
            endwhile;
            ?>
        </div>

        <div class="photos-grid">
            <div class="taxonomies">
                <!-- Sélectionner les termes de la taxonomie "Catégories" -->
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'categorie',
                    'hide_empty' => false,
                ));
                ?>
                <select id="category-filter">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category->slug); ?>" <?php selected(isset($_GET['category']) ? $_GET['category'] : '', $category->slug); ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Sélectionner les termes de la taxonomie "Formats" -->
                <?php
                $formats = get_terms(array(
                    'taxonomy' => 'format',
                    'hide_empty' => false,
                ));
                ?>
                <select id="format-filter">
                    <option value="">Tous les formats</option>
                    <?php foreach ($formats as $format) : ?>
                        <option value="<?php echo esc_attr($format->slug); ?>" <?php selected(isset($_GET['format']) ? $_GET['format'] : '', $format->slug); ?>>
                            <?php echo esc_html($format->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="photos-list">
    <?php
    // Arguments pour WP_Query pour récupérer les photos triées par date
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8, // Limiter à 8 photos
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // Vérifier s'il y a des filtres de catégorie ou de format
    $tax_query = array('relation' => 'AND');

    if (!empty($_GET['category'])) {
        $tax_query[] = array(
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['category']),
        );
    }

    if (!empty($_GET['format'])) {
        $tax_query[] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['format']),
        );
    }

    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    $photos_query = new WP_Query($args);

    if ($photos_query->have_posts()) :
        while ($photos_query->have_posts()) : $photos_query->the_post();
            $image = get_field('photo');
            if ($image) :
    ?>
                <div class="photo-item">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                    </a>
                </div>
    <?php
            endif;
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;
    ?>
</div>
<button id="load-more-photos" style="background-color: #D8D8D8; font-family: 'Space Mono', monospace; width: 272px; height: 50px; text-align: center; line-height: 50px; border: none; cursor: pointer;">
    Charger plus de photos
</button>

        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>
<script>
// Script JavaScript pour gérer le filtrage des photos par catégorie et format
jQuery(function($) {
    // Fonction pour mettre à jour l'URL en fonction des filtres sélectionnés
    function updateURL() {
        var category = $('#category-filter').val(); // Récupère la valeur sélectionnée du filtre de catégorie
        var format = $('#format-filter').val(); // Récupère la valeur sélectionnée du filtre de format

        var url = '<?php echo esc_url(home_url('/')); ?>'; // Base URL
        var params = []; // Tableau pour stocker les paramètres d'URL

        if (category) {
            params.push('category=' + category);
        }

        if (format) {
            params.push('format=' + format);
        }

        // Si des paramètres sont présents, les ajouter à l'URL
        if (params.length > 0) {
            url += '?' + params.join('&');
        }


        window.location.href = url; // Redirection vers la nouvelle URL
    }

    // Écouteur de changement pour le filtre de catégorie
    $('#category-filter').change(function() {
        updateURL(); // Mettre à jour l'URL lorsque le filtre de catégorie change
    });

    // Écouteur de changement pour le filtre de format
    $('#format-filter').change(function() {
        updateURL(); // Mettre à jour l'URL lorsque le filtre de format change
    });
});
</script>

<!-- <style> -->
/* CSS pour structurer la grille de photos */
/* .photos-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.photo-item {
    width: 564px;
    height: 495px;
    box-sizing: border-box;
    margin-bottom: 20px;
}
<!-- </style> */ -->
