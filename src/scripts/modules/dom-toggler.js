/**
 * Module for toggling classes via DOM attributes.
 *
 * @param  {object}  a  First object
 * @param  {object}  b  Second object
 * @return {object}     Merged object
 */

let $elements = [];

// Detect unsupported browsers ( <= IE10 )
const browserSupport = 'classList' in document.createElement( 'p' );

/**
 * Toggle multiple classes at once, as IE 10 and 11
 * do not support that in classList.
 *
 * @param  {array|string}  classNames
 */
const multiToggle = function( classNames = [] ) {
  const that = this;

  // turn string into array of class names
  if ( typeof classNames === 'string' ) {
    classNames = classNames.replace( ',', ' ' );
    classNames = classNames.split( /\s+/ );
  }

  [].forEach.call( classNames, function( className ) {
    that.classList.toggle( className );
  });
}

/**
 * Get all dom elements with a data-toggle attribute.
 */
const addElements = function() {
  $elements = document.querySelectorAll( '[data-toggle]' );
}

/**
 * Handler for toggling classes.
 */
const handleToggle = function( event ) {
  const selector = this.dataset.toggle || '';
  const classNames = this.dataset.toggleClass || 'is-active';
  const toggleSelf = this.dataset.toggleSelf != 'false';

  // Apply classes to targets
  if ( selector ) {
    [].forEach.call( document.querySelectorAll( selector ), function( el ) {
      multiToggle.call( el, classNames );
    });
  }

  // Apply active classe to self
  if ( toggleSelf ) {
    multiToggle.call( this, 'is-active' );
  }

  event.preventDefault();
}

/**
 * Add event listeners for all DOM elements.
 */
const addEvents = function() {
  [].forEach.call( $elements, function( elem ) {
    elem.addEventListener( 'click', handleToggle );
  })
}

/**
 * Remove event listeners for all DOM elements.
 */
const removeEvents = function() {
  [].forEach.call( $elements, function( elem ) {
    elem.removeEventListener( 'click', handleToggle );
  })
}

/**
 * Initialize module.
 */
const init = function() {
  if ( ! browserSupport ) return;
  addElements();
  addEvents();
}

/**
 * Destroy module.
 */
const destroy = function() {
  removeEvents();
  $elements = [];
}

/**
 * Refresh module (call after DOM change).
 */
const refresh = function() {
  destroy();
  init();
}

module.exports = {
  init,
  destroy,
  refresh
};
