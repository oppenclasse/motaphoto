<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>motaphoto</title>
    <?php wp_head(); // Appelle les hooks d'en-tête de WordPress ?>
        <!-- Inclure la police Space Mono -->
        <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    
<header>
        <div class="site-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
            </div>
            </a>
                <nav role="navigation" aria-label="<?php _e('Menu principal',   'text-domain'); ?>">
                        <?php
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container'      => false, // On retire le conteneur généré par WP
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu'
                    ));
                    ?>
                </nav>
</header>