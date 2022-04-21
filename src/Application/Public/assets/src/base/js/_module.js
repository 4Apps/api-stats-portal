import 'core-js/es/object/assign';
import 'customPolyfill';
import $ from 'jquery';
import Utils from 'utils';
import init from './init';

// Assign stuff to global context
window.$ = $;
window.jQuery = $;
window.Utils = Utils;

// Init app wide stuff
init();

// Export classes
export { $, Utils };
