<?php get_header(); ?>

<div id="primary">
    <div id="content" role="main" class="main-content">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="content-photo-wrapper">
                <div class="content-block">
                    <h2><?php the_title(); ?></h2>
                    <!-- Afficher le terme "Référence" -->
                    <p><strong>Référence :</strong> <?php the_field('reference'); ?></p>

                    <?php 
                    // Afficher les termes de la taxonomie "Catégories"
                    $categories = get_the_terms( get_the_ID(), 'categorie' );
                    if ( !empty($categories) && !is_wp_error($categories) ) {
                        $categorie_names = wp_list_pluck($categories, 'name');
                        echo '<p><strong>Catégorie :</strong> ' . implode(', ', $categorie_names) . '</p>';
                    }
                    ?>

                    <?php 
                    // Afficher les termes de la taxonomie "Formats"
                    $formats = get_the_terms( get_the_ID(), 'format' );
                    if ( !empty($formats) && !is_wp_error($formats) ) {
                        $format_names = wp_list_pluck($formats, 'name');
                        echo '<p><strong>Format :</strong> ' . implode(', ', $format_names) . '</p>';
                    }
                    ?>
                    <!-- Afficher le terme "Type" -->
                    <p><strong>Type :</strong> <?php the_field('type'); ?></p>
                    <p><strong>Année :</strong> <?php the_field('date'); ?></p>
                </div>
                <div class="photo-container">
                    <?php 
                    $image = get_field('photo');
                    if ($image) :
                    ?>
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="photo-image" />
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; // end of the loop. ?>
    </div><!-- #content -->
    <div class="footer-navigation">
        <a href="#" id="contact-link" data-photo-id="<?php the_ID(); ?>">Contact</a>
        <div class="nav-links">
            <?php 
            $prev_post = get_previous_post();
            if ($prev_post) :
            ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="navigation-link" data-thumbnail="<?php echo get_the_post_thumbnail_url($prev_post->ID); ?>">Précédent</a>
            <?php endif; ?>

            <?php 
            $next_post = get_next_post();
            if ($next_post) :
            ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="navigation-link" data-thumbnail="<?php echo get_the_post_thumbnail_url($next_post->ID); ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</div><!-- #primary -->

<?php get_footer(); ?>
