<?php
/**
 * Private Development page.
 *
 * @package Squire
 * @since 1.0.0
 */

$secret = get_secret();

if ( $secret && ! isset( $_GET[ $secret ] ) ) {
  header( $_SERVER[ "SERVER_PROTOCOL" ] . " 404 Not Found", true, 404 ); exit;
}
?>

This is a private development page. Feel free to experiment.