<?php
get_header(); 
?>

<div id="primary">
    <div id="content" role="main" style="display: flex; flex-direction: column; height: 100vh;">

        <?php while ( have_posts() ) : the_post(); ?>

            <div style="display: flex; flex-grow: 1;">
                <div style="width: 50%; padding: 20px;">
                    <h1><?php the_title(); ?></h1>

                    <p><strong>Réf. Photo:</strong> <?php the_field('photo_ref'); ?></p>

                    <?php 
                    // Afficher les termes de la taxonomie "Catégories"
                    $categories = get_the_terms( get_the_ID(), 'categorie' );
                    if ( !empty($categories) && !is_wp_error($categories) ) {
                        $categorie_names = wp_list_pluck($categories, 'name');
                        echo '<p><strong>Catégorie:</strong> ' . implode(', ', $categorie_names) . '</p>';
                    }
                    ?>

                    <?php 
                    // Afficher les termes de la taxonomie "Formats"
                    $formats = get_the_terms( get_the_ID(), 'format' );
                    if ( !empty($formats) && !is_wp_error($formats) ) {
                        $format_names = wp_list_pluck($formats, 'name');
                        echo '<p><strong>Format:</strong> ' . implode(', ', $format_names) . '</p>';
                    }
                    ?>

                    <p><strong>Date de prise de vue:</strong> <?php the_field('photo_date'); ?></p>
                </div>
                <div style="width: 50%; display: flex; justify-content: center; align-items: center;">
                    <?php 
                    $image = get_field('hero_image');
                    if ($image) :
                    ?>
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" style="max-width: 100%; max-height: 100%;" />
                    <?php endif; ?>
                </div>
            </div>

            <div style="height: 118px; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
                <a href="#" id="contact-link" data-photo-id="<?php the_ID(); ?>">Contactez-moi</a>
                <div>
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

        <?php endwhile; // end of the loop. ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>

<!-- Modal de contact -->
<div id="contact-modal" style="display: none;">
    <div>
        <h2>Contactez-moi</h2>
        <form>
            <label for="photo_ref">Réf. Photo</label>
            <input type="text" id="photo_ref" name="photo_ref" readonly>
            <!-- Autres champs de formulaire -->
            <button type="submit">Envoyer</button>
        </form>
        <button id="close-modal">Fermer</button>
    </div>
</div>

<script>
document.getElementById('contact-link').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('photo_ref').value = event.target.getAttribute('data-photo-id');
    document.getElementById('contact-modal').style.display = 'block';
});

document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('contact-modal').style.display = 'none';
});

document.querySelectorAll('.navigation-link').forEach(function(link) {
    link.addEventListener('mouseover', function() {
        var thumbnailUrl = link.getAttribute('data-thumbnail');
        // Afficher la miniature (par exemple, en modifiant l'arrière-plan d'un élément)
    });
});
</script>
