window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

window.Vue = require('vue');

window.moment = require('moment');
window.Chart = require('chart.js');

//inject csrf tokens to all (non-GET) requests
$.ajaxSetup({ headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}});
