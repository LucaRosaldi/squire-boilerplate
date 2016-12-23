<?php
/**
 * Site header.
 *
 * @package Squire
 * @since 1.0.0
 */
?>

<header class="p-topnav" role="navigation">
  <div class="p-topnav_content">
    <?php load_partial( 'nav', 'main' ); ?>
  </div>
</header>
<!-- / .p-topnav -->

<button
  class="p-topnav-toggle o-hamburger"
  data-toggle="body"
  data-toggle-class="topnav-is-visible overlay-is-visible"
  aria-hidden="true">
  <span></span>
</button>
