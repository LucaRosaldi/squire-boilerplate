<?php
/**
 * Vecchio Amaro del Capo main class.
 *
 * @package Squire
 * @since 1.0.0
 */

namespace Squire;

/**
 * Class App
 */
class App
{

  /**
   * @var string Theme version number.
   */
  const VERSION = '1.0.0';

  /**
   * @var string Uri of current request.
   * @access protected
   */
  protected $requestUri;

  /**
   * @var bool Whether multiple languages are defined.
   * @access protected
   */
  protected $is_multilanguage;

  /**
   * @var string Current language code ( 2 characters ).
   * @access protected
   */
  protected $languageCode;

  /**
   * @var string Name of current view.
   * @access protected
   */
  protected $viewName;

  /**
   * @var array All routes.
   * @access protected
   */
  protected $routes;

  /**
   * @var array Local strings and settings.
   * @access protected
   */
  protected $localization;

  /**
   * @var array Avalaible languages.
   * @access protected
   */
  protected $languages;

  /**
   * @var array Configuration options.
   * @access private
   */
  private $_config;

  /**
   * @var object Instance of this class.
   * @access public
   */
  public static $instance;

  /**
   * Return an instance of this class.
   *
   * @since 1.0.0
   * @access public
   *
   * @return  object  Class instance
   */
  public static function getInstance()
  {
    if ( self::$instance === null ) {
      self::init();
    }
    return self::$instance;
  }

  /**
   * Initiate class.
   *
   * @since 1.0.0
   * @access public
   */
  public static function init()
  {
    self::$instance = new self();
  }

  /**
   * Constructor.
   *
   * @since 1.0.0
   * @access public
   */
  public function __construct()
  {
    $this->_loadConfig();
    $this->_loadRouter();

    $this->_setCurrentUri();
    $this->_setLanguage();
    $this->_setLocalization();
    $this->_setRoutes();
    $this->_setView();
  }

  /**
   * Load global configuration.
   *
   * @since  1.0.0
   * @access private
   */
  private function _loadConfig()
  {
    $this->_config = $this->parseJSON( 'data/config.json' );
  }

  /**
   * Load configuration for router.
   *
   * @since  1.0.0
   * @access private
   */
  private function _loadRouter()
  {
    $this->_config[ 'router' ] = $this->parseJSON( 'data/router.json' );
  }

  /**
   * Set current request uri.
   *
   * @since  1.0.0
   * @access protected
   */
  protected function _setCurrentUri()
  {
    $root = $this->getRootUrl();
    $request = $_SERVER[ 'REQUEST_URI' ];

    // root url is not defined, use relative uris
    if ( ! $root ) {
      $this->requestUri = trim( $request, '/' );
    }

    // subtract the root from current url to define the request
    else {
      $root = explode( '//', $root )[ 1 ];
      $url = $_SERVER[ 'HTTP_HOST' ] . $request;
      $this->requestUri = trim( str_replace( $root, '', $url ), '/' );
    }
  }

  /**
   * Set language.
   *
   * @since  1.0.0
   * @access protected
   */
  protected function _setLanguage()
  {
    $langs = $this->_config[ 'lang' ];

    // set all languages
    $this->languages = array_combine( $this->pluck( $langs, 'code' ), $langs );

    // single language
    if ( count( $this->languages ) > 1 ) {
      $this->is_multilanguage = false;
      $this->languageCode = $langs[ 0 ][ 'code' ];
    }

    // multi language
    else {
      $this->is_multilanguage = true;

      // get default language
      $default = array_filter( $langs, function( $lang ) {
        return ! empty( $lang[ 'default' ] );
      })[ 0 ][ 'code' ];

      // get language code from the request uri
      $uri = $this->requestUri;
      if ( strlen( $uri ) > 2 && strpos( $uri, '/' ) === 2 ) {
        $uri = trim( substr( $uri, 0, 2 ), '/' );
      }

      $this->languageCode = ( $this->hasLanguage( $uri ) ) ? $uri : $default;
    }
  }

  /**
   * Set localized routes and strings for current language.
   *
   * For each available language there must be a corresponding JSON file
   * in the localization folder.
   *
   * @since  1.0.0
   * @access protected
   */
  protected function _setLocalization()
  {
    $this->localization = $this->parseJSON( "data/i18n/$this->languageCode.json" );
  }

  /**
   * Set routes for all languages.
   * Each route is structured as array of route name / view name.
   *
   * @since  1.0.0
   * @access private
   */
  protected function _setRoutes()
  {
    $routes = $this->_config[ 'router' ][ 'routes' ];
    foreach( $this->languages as $code => $lang ) {
      foreach ( $routes as $name => $route ) {

        // if uris are different for each language, get the right one
        if ( is_array( $route ) ) {
          $route = ( isset( $route[ $code ] ) ) ? $route[ $code ] : false;
        }

        // set the route if present
        if ( $route ) {
          $this->routes[ $code ][ $route ] = $name;
        }
      }
    }
  }

  /**
   * Set current view.
   *
   * @since  1.0.0
   * @access protected
   */
  protected function _setView()
  {
    $lang   = $this->languageCode;
    $routes = $this->routes[ $lang ];
    $view   = $this->requestUri;

    // subtract language
    if ( $this->is_multilanguage ) {
      $view = preg_replace( "/^$lang\//", "", $view . '/' );
    }

    // subtract query string
    $view = trim( explode( '?', $view )[ 0 ], '/' );

    // route is empty, return the home page
    if ( $view === '' ) {
      $this->viewName = 'home';
    }

    // route is not defined, return 404 page
    else if ( empty( $routes[ $view ] ) ) {
      $this->viewName = '404';
    }

    // route is found
    else {
      $this->viewName = $routes[ $view ];
    }
  }

  /**
   * Parse JSON file to associative array.
   *
   * @since  1.0.0
   * @access protected
   *
   * @param  string $file Path to file
   * @return array        Associative array
   */
  protected function parseJSON( $file )
  {
    return json_decode( file_get_contents( $file ), true );
  }

  /**
   * Remove slash from the end of a string.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  string $str
   * @return string
   */
  public function unslash( $str )
  {
    return rtrim( $str, '/' );
  }

  /**
   * Add a slash at the end of a string.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  string $str
   * @return string
   */
  public function slash( $str )
  {
    return $this->unslash( $str ) . '/';
  }

  /**
   * Join pieces of a path or a uri.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  array  $pieces     Array of pieces to join
   * @param  array  [$trailing] Whether to insert trailing slash
   * @return string             Joined path / uri
   */
  public function join( array $pieces, $trailing = false )
  {
    $str = trim( implode( '/', array_filter( $pieces ) ), '/' );
    if ( $trailing ) {
      return $this->slash( $str );
    }
    return $str;
  }

  /**
   * Pluck an array of values from an array.
   *
   * @param  array   $arr
   * @param  string  $key
   * @return array
   */
  public function pluck( array $arr, $key )
  {
    return array_map( function( $item ) use ( $key ) {
      return $item[ $key ];
    }, $arr );
  }

  /**
   * Check whether language is available in configuration.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  string $code Language code
   * @return bool
   */
  public function hasLanguage( $code )
  {
    return array_key_exists( $code, $this->languages );
  }

  /**
   * Get the root url as set in configuration.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getRootUrl() {
    return $this->_config[ 'root' ];
  }

  /**
   * Get the home url for a given language.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getHomeUrl( $lang = '' ) {
    if ( $this->is_multilanguage ) {
      $lang = ( $this->hasLanguage( $lang ) ) ? $lang : $this->languageCode;
      return $this->join( [ $this->getRootUrl(), $lang ], true );
    }
    return $this->getRootUrl();
  }

  /**
   * Get current uri.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getRequestUri()
  {
    return $this->requestUri;
  }

  /**
   * Return whether current user can view the site content.
   *
   * @since  1.0.0
   * @access public
   *
   * @return boolean
   */
  public function isAuthorized()
  {
    return $this->isAuthorized;
  }

  /**
   * Get all routes.
   *
   * @since  1.0.0
   * @access public
   *
   * @return array
   */
  public function getRoutes()
  {
    return $this->routes;
  }

  /**
   * Get current language code.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getLanguageCode()
  {
    return $this->languageCode;
  }

  /**
   * Get name of current view.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getViewName()
  {
    return $this->viewName;
  }

  /**
   * Get localized routes and strings for a given language.
   *
   * @since  1.0.0
   * @access public
   *
   * @return array
   */
  public function getLocalization() {
    return $this->localization;
  }

  /**
   * Get available languages.
   *
   * @since  1.0.0
   * @access public
   *
   * @return array
   */
  public function getLanguages()
  {
    return $this->languages;
  }

  /**
   * Retrieve url for a given route in the router object.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  string $type   Type of router object
   * @param  string $view   Key of the route
   * @param  string [$lang] Language code, defaults to current
   * @return string
   */
  public function getRouteUrl( $type, $key, $lang = '' ) {
    $router = $this->_config[ 'router' ];
    $home = $this->getRootUrl();
    $lang = ( $this->hasLanguage( $lang ) ) ? $lang : $this->languageCode;

    if ( ! isset( $router[ $type ][ $key ] ) ) {
      return '';
    }

    $route = $router[ $type ][ $key ];

    // get localized route, if present
    if ( is_array( $route ) ) {
      $route = ( isset( $route[ $lang ] ) ) ? $route[ $lang ] : '';
    }

    // return empty string if route is not defined
    if ( ! $route ) {
      return '';
    }

    // return original string if route is already a url
    if ( strpos( $route, '//' ) !== false ) {
      return $route;
    }

    // return localized url generated from the route
    if ( $this->is_multilanguage ) {
      return $this->join( [ $home, $lang, $route ], true );
    }
    return $this->join( [ $home, $route ], true );
  }

  /**
   * Get translated strings for all languages, divided by view.
   * Return a multidimensional array ( view > strings > languages ).
   *
   * @since  1.0.0
   * @access public
   *
   * @return array
   */
  public function getStrings()
  {
    $strings = [];

    // loop through each language
    foreach( $this->languages as $code => $lang ) {

      // parse localization file
      $l10n = $this->parseJSON( "data/lang/$code.json" );

      // tag each string with current language
      foreach ( $l10n as $name => $view ) {
        foreach ( $view as $key => $str ) {

          // skip items whose key begins with an underscore
          if ( strpos( $key, '_' ) !== 0 ) {
            $l10n[ $name ][ $key ] = [ $code => $str ];
          } else {
            unset( $l10n[ $name ][ $key ] );
          }

        }
      }

      // merge all strings
      $strings = array_merge_recursive( $strings, $l10n );
    }

    return $strings;
  }

  /**
   * Get url for an asset.
   *
   * @since  1.0.0
   * @access public
   *
   * @param  string  $type   Type of asset (see configuration file)
   * @param  string  [$file] File name of the asset (including extention)
   * @return string          Absolute URI for the asset
   */
  public function getAssetUrl( $type, $file = '' )
  {
    $root = $this->getRootUrl();
    $paths = $this->_config[ 'uri' ];

    if ( ! array_key_exists( $type, $paths ) ) {
      return;
    }

    $path = $this->join( [ $root, $paths[ $type ] ], true );

    if ( $file ) {
      $path = $path . $file;
    }

    return $path;
  }

  /**
   * Get secret token defined in configuration.
   *
   * @since  1.0.0
   * @access public
   *
   * @return string
   */
  public function getSecret()
  {
    return ( isset( $this->_config[ 'secret' ] ) ) ? $this->_config[ 'secret' ] : '';
  }

  /**
   * Include a view.
   *
   * Load current view if no argument is passed.
   *
   * Important: nested views replace the '/' (slash) with the '.' (dot) for the file name.
   * i.e. 'page/soobpage' includes the file 'page.soobpage.php' in the views folder.
   *
   * @since  1.0.0
   * @access public
   *
   * @param string [$name] Name of view to load, defaults to current view name
   */
  public function loadView( $name = '' )
  {
    $name = ( $name ) ?: $this->viewName;
    $pathname = $this->_config[ 'path' ][ 'view' ];
    $filename = str_replace( '/', '.', $name ) . '.php';

    @include $this->join( [ $pathname, $filename ] );
  }

  /**
   * Include a partial.
   *
   * @since  1.0.0
   * @access public
   *
   * @param string $name   Folder name or file name of partial to load
   * @param string [$name] Optional file name, if first param is folder
   */
  public function loadPartial( $path, $name = '' )
  {
    if ( $name ) {
      $path .= '/' . $path . '.' . basename( $name, '.php' );
    }

    $folder = $this->_config[ 'path' ][ 'partial' ];
    $file = "$folder/$path.php";

    @include $file;
  }

  /**
   * Include a component.
   *
   * @since  1.0.0
   * @access public
   *
   * @param string $name   Folder name or file name of component to load
   * @param string [$name] Optional file name, if first param is folder
   */
  public function loadComponent( $path, $name = '' )
  {
    if ( $name ) {
      $path .= '/' . $path . '.' . basename( $name, '.php' );
    }

    $folder = $this->_config[ 'path' ][ 'component' ];
    $file = "$folder/$path.php";

    @include $file;
  }

}
