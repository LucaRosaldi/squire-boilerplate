<?php
/**
 * Site closing tags.
 *
 * @package Squire
 * @since 1.0.0
 */
?>

    </div>
    <!-- / .p-view -->
  </div>
  <!-- / .p-site -->

  <?php load_partial( 'site', 'footer' ) ?>
  <?php load_partial( 'site', 'browsehappy' ) ?>

  <script>
    window.appConfig = {
      root: '<?php echo get_root_url() ?>',
      lang: '<?php echo get_language_code() ?>'
    }
  </script>

  <script src="<?php asset( 'js', 'app.js?v=' . get_version() ) ?>"></script>


</body>
</html>