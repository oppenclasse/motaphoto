<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="/mentions-legales">Mentions légales</a>
            <a href="/privacy-policy">Vie privée</a>
        </div>
        <div class="footer-text">
            <p>&copy; <?php echo date("Y"); ?> Tous droits réservés.</p>
        </div>
    </div>
    <!-- bouton modal -->
    <button id="contactBtn">Contactez-nous</button>

</footer>
<?php wp_footer(); ?>
<!-- modal  -->
<?php get_template_part('templates_part/contact-modal'); ?>

</body>
</html>