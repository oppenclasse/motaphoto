<?php
// Ajout de style CSS
function my_theme_enqueue_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// Enqueue des scripts
function theme_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

// Enqueue script personnalisé
function my_theme_enqueue_scripts() {
    wp_enqueue_script('my-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');

// Ajout du menu
function register_my_menu() {
    register_nav_menu( 'main-menu', __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );


// Fonction pour charger plus de photos via AJAX
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

function load_more_photos() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $page,
    );

    $photos_query = new WP_Query($args);
    ob_start();

    if ($photos_query->have_posts()) {
        $count = 0;
        while ($photos_query->have_posts()) {
            $photos_query->the_post();
            $image = get_field('photo');
            if ($image) {
                if ($count % 2 == 0) {
                    echo '<div class="photos-row">';
                }
                ?>
                <div class="photo-item">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                    </a>
                </div>
                <?php
                $count++;
                if ($count % 2 == 0) {
                    echo '</div>';
                }
            }
        }
        if ($count % 2 != 0) {
            echo '</div>';
        }
    }

    wp_reset_postdata();
    $response['photos'] = ob_get_clean();
    wp_send_json_success($response);
}

add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );
function my_enqueue_scripts() {
    wp_enqueue_script( 'my-custom-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true );

    // Localiser ajaxurl dans le script JavaScript
    wp_localize_script( 'my-custom-scripts', 'my_ajax_obj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'my-special-string' ),
    ));
}

