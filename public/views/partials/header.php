<?php
/**
 * Site open.
 *
 * @package Squire
 * @since 1.0.0
 */
?>
<!doctype html>
<html class="no-js" lang="<?php echo get_language_code() ?>">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">

  <title><?php trans( 'head-title' ) ?></title>
  <meta name="description" content="<?php trans( 'head-description' ) ?>">
  <meta name="keywords" content="<?php trans( 'head-keywords' ) ?>">

  <?php language_link_tags() ?>

  <link rel="stylesheet" href="<?php asset( 'css', 'app.css?v=' . get_version() ) ?>">

</head>

<body class="is-loading">

  <?php load_partial( 'site', 'icons' ) ?>
  <?php load_partial( 'site', 'loader' ) ?>

  <div class="p-site">
    <div class="p-view <?php view_classes() ?>">
