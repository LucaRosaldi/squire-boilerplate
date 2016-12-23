<?php
/**
 * Site functions.
 *
 * @package Squire
 * @since 1.0.0
 */

use \Squire\App as App;

/**
 * Get current version.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_version() {
  return App::VERSION;
}

/**
 * Get secret token defined in configuration.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_secret() {
  return App::getInstance()->getSecret();
}

/**
 * Get current language code.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_request_uri() {
  return App::getInstance()->getRequestUri();
}

/**
 * Get current language code.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_language_code() {
  return App::getInstance()->getLanguageCode();
}

/**
 * Get available languages.
 *
 * @since  1.0.0
 *
 * @return array
 */
function get_languages() {
  return App::getInstance()->getLanguages();
}

/**
 * Check whether language is available in configuration.
 *
 * @since  1.0.0
 *
 * @param  string $code Language code
 * @return bool
 */
function has_language( $code ) {
  return App::getInstance()->hasLanguage( $code );
}

/**
 * Get current view name.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_view_name() {
  return App::getInstance()->getViewName();
}

/**
 * Get localized routes and strings for a given language.
 *
 * @since  1.0.0
 *
 * @return array
 */
function get_view_strings( $view = '' ) {
  return App::getInstance()->getLocalization();
}

/**
 * Get url to the root domain.
 *
 * @return string
 */
function get_root_url() {
  return App::getInstance()->getRootUrl();
}

/**
 * Get permalink to the home page in a given language.
 *
 * @since  1.0.0
 *
 * @param  string [$lang] Language code, defaults to current
 * @return string
 */
function get_home_url( $lang = '' ) {
  return App::getInstance()->getHomeUrl( $lang );
}
  function home_url( $lang = '' ) {
    echo get_home_url( $lang );
  }

/**
 * Get permalink to a view.
 *
 * Return link to home page if view is not defined.
 *
 * @since  1.0.0
 *
 * @param  string  $view      Name of view
 * @param  string  [$lang]    Optional language code, defaults to current
 * @param  boolean [$fb_home] Optional fallback to home page url, defaults true
 * @return string
 */
function get_url( $view, $lang = '', $fb_home = true ) {
  $url = App::getInstance()->getRouteUrl( 'routes', $view, $lang );
  if ( ! $url && $fb_home ) {
    return get_home_url( $lang );
  }
  return $url;
}
  function url( $view, $lang = '', $fb_home = true ) {
    echo get_url( $view, $lang, $fb_home );
  }

/**
 * Get permalink to an external resource defined in the router.
 *
 * Return empty link if route is not defined.
 *
 * @since  1.0.0
 *
 * @param  string $name   Name of route
 * @param  string [$lang] Language code, defaults to current
 * @return string
 */
function get_external_url( $name, $lang = '' ) {
  return ( App::getInstance()->getRouteUrl( 'external', $name, $lang ) ) ?: '#';
}
  function external_url( $name, $lang = '' ) {
    echo get_social_url( $name, $lang );
  }

/**
 * Get all routes.
 *
 * @since  1.0.0
 *
 * @return array
 */
function get_routes() {
  return App::getInstance()->getRoutes();
}

/**
 * Get translated string.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_trans( $key, $view = '' ) {
  $l10n = App::getInstance()->getLocalization();
  $view = ( $view ) ?: get_view_name();
  return ( isset( $l10n[ $view ][ $key ] ) ) ? $l10n[ $view ][ $key ] : '';
}
  function trans( $key, $view = '' ) {
    echo get_trans( $key, $view );
  }

/**
 * Get absolute URI to an asset.
 *
 * If file is not passed, the uri to the asset folder is returned.
 *
 * @param  string  $type   Type of asset.
 * @param  string  [$file] File name of the asset (including extention)
 * @return string          Absolute URI for the asset
 */
function get_asset( $type, $file = '' ) {
  return App::getInstance()->getAssetUrl( $type, $file );
}
  function asset( $type, $file ) {
    echo get_asset( $type, $file );
  }

/**
 * Include a view.
 *
 * Load current view if no argument is passed.
 *
 * @since  1.0.0
 *
 * @param string [$name] Optional name of view to load, defaults to current view name
 */
function load_view( $name = '' ) {
  App::getInstance()->loadView( $name );
}

/**
 * Include a partial.
 *
 * @since  1.0.0
 *
 * @param string $name   Folder name or file name of partial to load
 * @param string [$name] Optional file name, if first param is folder
 */
function load_partial( $path, $name = '' ) {
  App::getInstance()->loadPartial( $path, $name );
}

/**
 * Include a component.
 *
 * @since  1.0.0
 *
 * @param string $name   Folder name or file name of component to load
 * @param string [$name] Optional file name, if first param is folder
 */
function load_component( $path, $name = '' ) {
  App::getInstance()->loadComponent( $path, $name );
}

/**
 * Check if the current page is the main page,
 * i.e. request uri is empty.
 *
 * @since  1.0.0
 *
 * @return string
 */
function is_main_page() {
  return ! get_request_uri();
}

/**
 * Check whether a given language code equals the current language.
 *
 * @since  1.0.0
 *
 * @return boolean
 */
function is_current_language( $code ) {
  return $code === get_language_code();
}

/**
 * Get info for current language.
 *
 * @since  1.0.0
 *
 * @return array
 */
function get_current_language() {
  $langs = get_languages();
  $code = get_language_code();
  return $langs[ $code ];
}

/**
 * Set data for the current view.
 *
 * @param array $data
 */
function set_view_data( array $data ) {
  $GLOBALS[ 'View' ] = $data;
}

/**
 * Get data for the current view.
 *
 * @since  1.0.0
 *
 * @param  string       [$key]  Optional key to retrieve in data array
 * @param  string       [$name] Optional name of view, defaults to current
 * @return array|string
 */
function get_view_data( $key = '' ) {
  $view = $GLOBALS[ 'View' ];
  if ( isset( $view[ $key ] ) ) {
    return $view[ $key ];
  }
  return ( ! empty( $view ) ) ? $view : [];
}

/**
 * Get current url.
 *
 * @since  1.0.0
 *
 * @return string
 */
function get_current_url() {
  $prot = ( $_SERVER[ 'SERVER_PORT' ] == 80 ) ? 'http://' : 'https://';
  $host = $_SERVER[ 'HTTP_HOST' ];
  $uri  = $_SERVER[ 'REQUEST_URI' ];
  return $prot . $host . $uri;
}

/**
 * Redirect user to a given view.
 *
 * Redirect to home page if view is not found in local routes.
 *
 * @since  1.0.0
 *
 * @param  string       $view    Name of view
 * @param  string       [$lang]  Language code, defaults to current
 * @param  string|array [$query] Additional query string to append
 */
function redirect_to( $view, $lang = '', $query = '' ) {
  $url = get_url( $view, $lang );

  if ( ! empty( $query ) ) {
    $query = ( is_array( $query ) ) ? http_build_query( $query ) : urlencode( $query );
    $url .= "?$query";
  }

  header( "Location: $url" );
  exit;
}

/**
 * Print all <link> tags for alternate languages.
 *
 * @since 1.0.0
 */
function language_link_tags() {
  $langs = get_languages();

  // return if there is only one language set
  if ( count( $langs ) === 1 ) {
    return;
  }

  $curr  = get_language_code();
  $view  = get_view_name();

  // remove current language
  unset( $langs[ $curr ] );

  if ( empty( $langs ) ) {
    return;
  }

  // print tag
  foreach( $langs as $lang ) {
    $code = $lang[ 'code' ];
    $url = get_url( $view, $code, false );
    if ( $url ) {
      echo "<link rel=\"alternate\" hreflang=\"$code\" href=\"$url\">";
    }
  }
}

/**
 * Print string with css classes for the current view.
 *
 * @since  1.0.0
 *
 * @return string
 */
function view_classes() {
  $classes = [];

  // add names of current and parent views
  $names = explode( '/', get_view_name() );

  $n = count( $names ) - 1;
  for ( $i = $n; $i >= 0; $i-- ) {
    $name = "v-" . $names[ $i ];
    $name = ( $i === $n ) ? $name : $name . "-parent";
    $classes[] = $name;
  }

  echo trim( implode( ' ', $classes ) );
}

/**
 * Check whether currently in a page view.
 * Optionally match children views in the check.
 *
 * @since  1.0.0
 *
 * @param  string  $name           Name of view to check
 * @param  boolean $match_children Whether to match children views
 * @return boolean
 */
function is_current_view( $name, $match_children = false ) {
  $view_name = get_view_name();
  if ( $match_children ) {
    return preg_match( "/^$name/", $view_name );
  }
  return $name === $view_name;
}

/**
 * Output classes if first argument matches the name of current view.
 *
 * @since  1.0.0
 *
 * @param $view_name Name of view to check against current
 * @param [$classes] Classes to output if name passes the check
 */
function view_class( $view_name, $classes = 'is-active' ) {
  if ( is_current_view( $view_name, true ) ) {
    echo " $classes";
  }
}

/**
 * Get all registered routes for a given language.
 *
 * @since  1.0.0
 *
 * @param  string [$lang] Optional language code, defaults to current
 * @return array
 */
function get_local_routes( $lang = '' ) {
  if ( ! $lang || ! has_language( $lang ) ) {
    $lang = get_language_code();
  }
  return get_routes()[ $lang ];
}

/**
 * Get routes which are children of a specific route.
 *
 * @param  string $parent Name of parent route
 * @param  string [$lang] Optional language code, defaults to current
 * @return array
 */
function get_children_routes( $parent, $lang = '' ) {
  $parent = trim( $parent, '/' ) . '/';
  return array_filter( get_local_routes( $lang ), function( $k ) use ( $parent ) {
    return substr( $k, 0, strlen( $parent ) ) === $parent;
  }, ARRAY_FILTER_USE_KEY );
}

/**
 * Print on the screen the CSV for all translations.
 *
 * @since  1.0.0
 */
function export_translation_csv() {
  $strings = App::getInstance()->getStrings();
  $rows = [];

  // first row which holds column names
  $codes = array_keys( get_languages() );
  $rows[] = array_merge( [ 'page', 'key' ], $codes );

  // blueprint for all languages
  $blueprint = array_fill_keys( $codes, '' );

  // fill rows
  foreach ( $strings as $name => $view ) {
    foreach ( $view as $key => $trans ) {
      $trans = array_merge( $blueprint, $trans );
      $rows[] = array_merge( [ $name, $key ], $trans );
    }
  }

  // encode row columns
  $rows = array_map( function( $v ) {
    return '"' . implode( '","', $v ) . '"';
  }, $rows );

  // separate rows
  $rows = implode( "<br>", $rows );

  // print encoded csv
  echo $rows;
}

/**
 * Import CSV with all translations and overwrite the language JSON file.
 *
 * IMPORTANT: File must be placed in the root folder.
 *
 * @since  1.0.0
 *
 * @param string $file Name of file
 */
function import_translation_csv( $filename ) {
  $encoded = json_encode( $strings, JSON_PRETTY_PRINT );
  var_dump( json_decode( $encoded, true ) );
}

/**
 * Get id of Iubenda privacy and cookie policy for a given language.
 *
 * @param  string [$lang] Language code
 * @return string         Policy id
 */
function get_iubenda_id( $lang = '' ){
  $code = ( has_language( $lang ) ) ? $lang : get_language_code();
  $lang = get_languages()[ $code ];
  return ( isset( $lang[ 'iubenda_id' ] ) ) ? $lang[ 'iubenda_id' ] : '';
}
