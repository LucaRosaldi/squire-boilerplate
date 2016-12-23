/**
 * Load a script asynchronously.
 *
 * @param  {string}  src  The script uri
 */

export default function loadScript( src ) {
  let script = document.createElement( 'script' );
  script.type = 'text/javascript';
  script.async = 'true';
  script.src = src;
  document.getElementsByTagName( 'head' )[0].appendChild( script );
}
