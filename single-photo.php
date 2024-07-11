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
            <button class="btn" id="contact-link" data-photo-id="<?php the_ID(); ?>">
                Contact
            </button>
        </div>
        <!-- Bloc à droite -->
        <div class="nav-links">
            <?php 
            // lien vers le post précédent
            $prev_post = get_previous_post();
            if ($prev_post) :
                $prev_image = get_field('photo', $prev_post->ID); // Récupérer l'image du post précédent
                $prev_thumbnail = $prev_image ? $prev_image['sizes']['thumbnail'] : get_the_post_thumbnail_url($prev_post->ID, 'thumbnail');
            ?>
                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="navigation-link prev-link" data-thumbnail="<?php echo $prev_thumbnail; ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/flechegauche.png" alt="Flèche gauche">
                    <div class="thumbnail-preview prev-thumbnail">
                        <img src="<?php echo esc_url($prev_thumbnail); ?>" alt="Miniature précédente">
                    </div>
                </a>
            <?php endif; ?>
            
            <?php 
            // lien vers le post suivant
            $next_post = get_next_post();
            if ($next_post) :
                $next_image = get_field('photo', $next_post->ID); // Récupérer l'image du post suivant
                $next_thumbnail = $next_image ? $next_image['sizes']['thumbnail'] : get_the_post_thumbnail_url($next_post->ID, 'thumbnail');
            ?>
                <a href="<?php echo get_permalink($next_post->ID); ?>" class="navigation-link next-link" data-thumbnail="<?php echo $next_thumbnail; ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/flechedroite.png" alt="Flèche droite">
                    <div class="thumbnail-preview next-thumbnail">
                        <img src="<?php echo esc_url($next_thumbnail); ?>" alt="Miniature suivante">
                    </div>
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

<!-- Modale -->
<div id="contactModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <?php echo do_shortcode('[contact-form-7 id="5adbf94" title="Formulaire de contact 1"]'); ?>
    </div>
</div>

<!-- CSS pour la modale -->
<style>
.modal {
    display: none; /* Caché par défaut */
    position: fixed; /* Reste en place même si on fait défiler la page */
    z-index: 1; /* Au-dessus de tout le contenu */
    left: 0;
    top: 0;
    width: 100%; /* Largeur totale de l'écran */
    height: 100%; /* Hauteur totale de l'écran */
    overflow: auto; /* Si le contenu est trop grand, ajoute une barre de défilement */
    background-color: rgb(0,0,0); /* Couleur de fond */
    background-color: rgba(0,0,0,0.4); /* Couleur de fond avec transparence */
}
.modal-content {
    background-color: #fefefe; /* Couleur de fond de la modale */
    margin: 15% auto; /* Centrer verticalement */
    padding: 20px; /* Espace intérieur */
    border: 1px solid #888; /* Bordure */
    width: 80%; /* Largeur de la modale */
}
.close {
    color: #aaa; /* Couleur de la croix de fermeture */
    float: right; /* Alignée à droite */
    font-size: 28px; /* Taille de la police */
    font-weight: bold; /* Gras */
}
.close:hover,
.close:focus {
    color: black; /* Couleur au survol */
    text-decoration: none; /* Pas de soulignement */
    cursor: pointer; /* Curseur de la souris */
}
.thumbnail-preview {
    display: none; /* Caché par défaut */
    position: absolute; /* Position absolue pour le placer par rapport à la flèche */
    bottom: 100%; /* Au-dessus de la flèche */
    left: 50%; /* Centré horizontalement */
    transform: translateX(-50%); /* Ajustement pour centrer */
    width: 80px; /* Largeur de la miniature */
    height: 70px; /* Hauteur de la miniature */
    z-index: 2; /* Au-dessus de tout le reste */
    background-color: #fff; /* Fond blanc */
    border: 1px solid #ccc; /* Bordure grise */
    padding: 2px; /* Petit padding autour de l'image */
}
.navigation-link {
    position: relative; /* Pour positionner correctement la miniature */
}
.navigation-link:hover .thumbnail-preview {
    display: block; /* Afficher la miniature au survol */
}
</style>

<!-- JavaScript pour la modale -->
<script>
// Récupérer les éléments de la modale
var modal = document.getElementById("contactModal");
var btn = document.getElementById("contact-link");
var span = document.getElementsByClassName("close")[0];

// Quand l'utilisateur clique sur le bouton, ouvrir la modale
btn.onclick = function(event) {
    event.preventDefault();
    var reference = "<?php the_field('reference'); ?>"; // Récupérer la valeur de la référence
    modal.style.display = "block";
    
    // Ajouter la référence au champ "Réf PHOTO" du formulaire Contact Form 7
    document.querySelector('[name="your-subject"]').value = reference;
}

// Quand l'utilisateur clique sur la croix, fermer la modale
span.onclick = function() {
    modal.style.display = "none";
}

// Quand l'utilisateur clique en dehors de la modale, fermer la modale
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Gérer l'affichage des miniatures au survol des flèches
var prevLink = document.querySelector('.prev-link');
var nextLink = document.querySelector('.next-link');
var prevThumbnail = prevLink.querySelector('.prev-thumbnail');
var nextThumbnail = nextLink.querySelector('.next-thumbnail');

prevLink.onmouseover = function() {
    prevThumbnail.style.display = 'block';
}

prevLink.onmouseout = function() {
    prevThumbnail.style.display = 'none';
}

nextLink.onmouseover = function() {
    nextThumbnail.style.display = 'block';
}

nextLink.onmouseout = function() {
    nextThumbnail.style.display = 'none';
}
</script>

<?php get_footer(); ?>
