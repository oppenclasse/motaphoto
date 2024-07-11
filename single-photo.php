<?php get_header(); ?>

<div id="primary">
    <div class="titre-photo-container">
        <div class="titre-container">
            <h2><?php the_title(); ?></h2>
            <!-- Afficher le champ "Référence" -->
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
            <!-- Afficher le champ "Type" -->
            <p><strong>Type :</strong> <?php the_field('type'); ?></p>
            <p><strong>Année :</strong> <?php the_field('date'); ?></p>
        </div>
        <!-- Afficher la photo associée -->
        <div class="photo-container">
            <?php 
            $image = get_field('photo');
            if ($image) :
            ?>
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="photo-image" />
            <?php endif; ?>
        </div>
    </div><!-- Fin de la classe titre-photo-container -->

    <div class="contact">
        <!-- Bloc à gauche -->
        <div class="contact-interesse">
            <p>Cette photo vous intéresse ? </p>
            <button class="btn">
                <a href="#" id="contact-link" data-photo-id="<?php the_ID(); ?>" class="contact-button">Contact</a>
            </button>
        </div>
        <!-- Bloc à droite -->
        <div class="nav-links">
            <?php 
            // lien vers le post précédent
            $prev_post = get_previous_post();
            if ($prev_post) :
                $prev_thumbnail = get_field('photo', $prev_post->ID);

            ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="navigation-link" data-thumbnail="<?php echo $prev_thumbnail['sizes']['thumbnail']; ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/flechegauche.png" alt="Flèche gauche">
                </a>
            <?php endif; ?>
                
                <div class="thumbnail-preview">
                    <img src="" alt="thumbnail">
                </div>
            <?php 
            // lien vers le post suivant
            $next_post = get_next_post();
            if ($next_post) :
                $next_thumbnail = get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
            ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="navigation-link" data-thumbnail="<?php echo $next_thumbnail['sizes']['thumbnail']; ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/flechedroite.png" alt="Flèche droite">
                </a>
            <?php endif; ?>
        </div>
    </div><!-- Fin du bloc de contact -->

    <!-- Bloc des photos apparentées -->
    <div class="apparentees">
        <div class="apparentees-titre">
            <p>Vous aimerez aussi</p>
        </div>
        <div class="apparentees-photo">
            <?php
            // Récupérer les IDs des catégories
            if ( !empty($categories) && !is_wp_error($categories) ) {
                $categorie_ids = wp_list_pluck($categories, 'term_id');

                // Arguments pour WP_Query
                $args = array(
                    'post_type' => 'photo', // Type de post à utiliser
                    'posts_per_page' => 2,
                    'post__not_in' => array(get_the_ID()), // Exclure le post actuel
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie',
                            'field'    => 'term_id',
                            'terms'    => $categorie_ids,
                        ),
                    ),
                );

                $related_query = new WP_Query( $args );

                if ( $related_query->have_posts() ) :
                    while ( $related_query->have_posts() ) : $related_query->the_post();
                        $related_image = get_field('photo');
                        if ( $related_image ) :
            ?>
                        <div>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($related_image['url']); ?>" alt="<?php echo esc_attr($related_image['alt']); ?>">
                            </a>
                        </div>
            <?php
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
            }
            ?>
        </div>
    </div><!-- Fin du bloc des photos apparentées -->
</div> <!-- #primary -->

<?php get_footer(); ?>
