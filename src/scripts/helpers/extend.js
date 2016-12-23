/**
 * Merge two objects.
 *
 * @param  {object}  a  First object
 * @param  {object}  b  Second object
 * @return {object}     Merged object
 */

export default function extend( a, b ) {
  for( var key in b ) {
    if( b.hasOwnProperty( key ) ) {
      a[ key ] = b[ key ];
    }
  }
  return a;
}
