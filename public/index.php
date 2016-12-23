<?php
/**
 * Main file.
 *
 * @package Squire
 * @since 1.0.0
 */

define( 'ABSPATH', __DIR__ );
require ABSPATH . '/inc/app.php';
require ABSPATH . '/inc/helpers.php';

load_view();
