import 'core-js/es/object/assign';
import 'customPolyfill';
import $ from 'jquery';
import Utils from 'utils';

// Assign stuff to global context
window.$ = $;
window.jQuery = $;
window.Utils = Utils;

// Require few other libraries
require('bootstrap/dist/js/bootstrap');

// Export classes
export { $, Utils };
