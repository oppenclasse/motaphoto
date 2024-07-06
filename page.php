<?php
get_header();
?>

<div id="hero-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/hero.jpg'); height: 100vh; background-size: cover; background-position: center;">
</div>

<div id="filters">
    <label for="category-filter">Catégories :</label>
    <select id="category-filter">
        <option value="">Toutes</option>
        <?php
        $categories = get_terms('categorie');
        foreach ($categories as $category) {
            echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
        }
        ?>
    </select>

    <label for="format-filter">Formats :</label>
    <select id="format-filter">
        <option value="">Tous</option>
        <?php
        $formats = get_terms('format');
        foreach ($formats as $format) {
            echo '<option value="' . $format->slug . '">' . $format->name . '</option>';
        }
        ?>
    </select>

    <label for="date-sort">Trier par :</label>
    <select id="date-sort">
        <option value="desc">Plus récentes</option>
        <option value="asc">Plus anciennes</option>
    </select>
</div>

<div id="photo-list">
    <!-- Les photos seront chargées ici via JavaScript -->
</div>

<button id="load-more">Charger plus</button>

<?php
get_footer();
?>
