/**
 * Squire JS
 *
 * This is the main scripts file.
 *
 * Author: PubliOne S.r.l.
 * Author URI: http://publione.it
 *
 * @version  0.1.0
 */

// Helpers
import extend from './helpers/extend';
import loadScript from './helpers/loadScript';

// Modules
import DOMToggler from './modules/dom-toggler';

// Node modules
import throttle from 'lodash/throttle';

( function ( window, document, undefined ) {
  'use strict';

  /*----------------------------------------------------------------------------*\
     VARIABLES
  \*----------------------------------------------------------------------------*/

  /**
   * Common DOM elements.
   */
  const htmlEl = document.getElementsByTagName( 'html' )[0];
  const headEl = document.getElementsByTagName( 'head' )[0];
  const bodyEl = document.getElementsByTagName( 'body' )[0];

  /**
   * Website config.
   */
  const config = window._config || {};


  /*----------------------------------------------------------------------------*\
     HELPERS
  \*----------------------------------------------------------------------------*/

  /**
   * Helper for event preventDefault()
   *
   * @param  {object} event
   */
  const preventDefault = function( event ) {
    event.preventDefault();
    event.stopPropagation();
  }

  /**
   * Helper for Array.prototype.forEach
   *
   * @param  {nodeList}
   * @param  {callback}
   */
  const forEach = function( nodeList, callback ) {
    [].forEach.call( nodeList, callback );
  }

  /*----------------------------------------------------------------------------*\
     LIBRARIES
  \*----------------------------------------------------------------------------*/


  /*----------------------------------------------------------------------------*\
     FUNCTIONS
  \*----------------------------------------------------------------------------*/

  /**
   * Scroll window to the top.
   */
  const pageScrollTop = function() {
    document.body.scrollTop = 0;
  }

  /**
   * Disable page scrolling.
   */
  const disableScrolling = function() {
    bodyEl.classList.add( 'no-scroll' );
  }

  /**
   * Enable page scrolling.
   */
  const enableScrolling = function() {
    bodyEl.classList.remove( 'no-scroll' );
  }

  /**
   * Show page loader.
   */
  const showPageLoader = function() {
    bodyEl.classList.add( 'is-loading' );
  }

  /**
   * Hide page loader.
   */
  const hidePageLoader = function() {
    bodyEl.classList.remove( 'is-loading' );
  }

  /**
   * Open offcanvas menu.
   */
  const showOffcanvas = function() {
    bodyEl.classList.add( 'offcanvas-is-visible' );
    bodyEl.classList.add( 'overlay-is-visible' );
  }

  /**
   * Close offcanvas menu.
   */
  const hideOffcanvas = function( caller ) {
    bodyEl.classList.remove( 'offcanvas-is-visible' );
    bodyEl.classList.remove( 'overlay-is-visible' );
  }

  /**
   * Add target="_blank" and rel="noopener" attributes to all external links.
   */
  const setExternalLinks = function( domain ) {
    if ( ! domain ) return;
    const links = document.querySelectorAll( 'a:not([href^="' + domain + '"]):not([href^="#"])' );
    [].forEach.call( links, function( link ) {
      link.setAttribute( 'target', '_blank' );
      link.setAttribute( 'rel', 'noopener' );
    });
  }

  /**
   * Initialize UI components.
   */
  const UIComponentsInit = function() {
    DOMToggler.init();
  }


  /*----------------------------------------------------------------------------*\
     EVENT LISTENERS
  \*----------------------------------------------------------------------------*/

  /**
   * Listener for window load event.
   */
  function windowLoadListeners() {
    window.addEventListener( 'load', function() {

      // remove loading class
      bodyEl.classList.remove( 'is-loading' );

      // initialize UI components
      UIComponentsInit();

    });
  }


  /*----------------------------------------------------------------------------*\
     CONSTRUCTOR
  \*----------------------------------------------------------------------------*/

  /**
   * Initiate all the event listeners.
   */
  function init() {
    windowLoadListeners();
  }

  /**
   * Constructor.
   */
  return { init: init() };

} )( window, window.document );
