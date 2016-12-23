<?php
/**
 * Main footer for the site.
 *
 * @package Squire
 * @since 1.0.0
 */
?>

<footer class="p-footer">
  <div class="p-footer_content">

    <div class="c-colophon">

      <div>
        <a
          class="c-colophon_logo"
          href="http://www.example.com">
          <img
            src="//placehold.it/40x40"
            alt="">
        </a>
      </div>

      <div class="c-colophon_legal">
        &copy; <?php echo date( 'Y' ) ?>
        <?php trans( 'footer-holding', 'site' ) ?>.
        <?php trans( 'footer-copyright', 'site' ) ?>.
        <?php trans( 'footer-vat', 'site' ) ?> IT00000000000
      </div>

      <div>

        <a
          class="c-colophon_credits"
          href="https://publione.it"><?php
          trans( 'footer-credits', 'site' )
      ?></a> &middot;

        <?php if ( $iubenda_id = get_iubenda_id() ) : ?>

        <a
          class="c-colophon_privacy"
          href="https://www.iubenda.com/privacy-policy/<?php echo $iubenda_id ?>/"><?php
          trans( 'footer-privacy', 'site' )
      ?></a>

        <script type="text/javascript">
          var _iub = _iub || [];
          _iub.csConfiguration = {
            cookiePolicyId: <?php echo $iubenda_id ?>,
            siteId: 659822,
            lang: "<?php echo get_language_code() ?>"
          };
        </script>

        <script src="//cdn.iubenda.com/cookie_solution/safemode/iubenda_cs.js" async></script>

        <?php else : ?>

        <a
          class="c-colophon_privacy"
          href="#"><?php
          trans( 'footer-privacy', 'site' )
      ?></a>

        <?php endif; ?>

      </div>

    </div>

  </div>
</footer>
