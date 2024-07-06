<?php
// ajoue style css
function my_theme_enqueue_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function theme_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');



// ajoue du menu
function register_my_menu() {
    register_nav_menu( 'main-menu', __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );

// taxonomie 
function create_photo_taxonomies() {
    register_taxonomy('format', 'attachment', array(
        'label' => 'Formats',
        'rewrite' => array('slug' => 'format'),
        'hierarchical' => true,
    ));
}
add_action('init', 'create_photo_taxonomies');

//  les fonctions pour gérer les requêtes AJAX.
function load_photos() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'desc';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 8,
        'paged' => $page,
        'orderby' => 'date',
        'order' => $order,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $photos = new WP_Query($args);

    if ($photos->have_posts()) {
        while ($photos->have_posts()) {
            $photos->the_post();
            $image_url = wp_get_attachment_image_src(get_the_ID(), 'thumbnail');
            ?>
            <div class="photo-item">
                <img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php the_title(); ?>" />
                <div class="overlay">
                    <a href="<?php the_permalink(); ?>" class="icon-eye">Voir</a>
                    <a href="<?php echo esc_url($image_url[0]); ?>" class="icon-fullscreen" data-lightbox="image">Plein écran</a>
                </div>
            </div>
            <?php
        }
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_load_photos', 'load_photos');
add_action('wp_ajax_nopriv_load_photos', 'load_photos');

// ajouter la variable ajaxurl pour les requêtes AJAX.
function enqueue_custom_scripts() {
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
    wp_localize_script('custom-js', 'ajaxurl', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

