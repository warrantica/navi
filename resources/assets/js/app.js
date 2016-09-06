/*==============================================================================
Bootstrap Vue, jQuery, API and injecting requests with CSRF tokens
==============================================================================*/

require('./bootstrap');
window.Navi = require('./naviAPI.js');

/*==============================================================================
Components
==============================================================================*/

//Vue.components(example, require('./components/example.vue'));

/*==============================================================================
Vue Instance
==============================================================================*/

let App = new Vue({
  el: 'body',

  data: {
    symbol: '',
    name: '',
    nav: '0.0000'
  },

  methods: {
    getNav(){
      // Navi.getNav(this.symbol).then(value => {
      //   console.log(value);
      //   this.name = value.name;
      //   this.nav = value.nav;
      // });
      Navi.getHistoricalNav(this.symbol).then(value => {
        console.log(value);
      });
    }
  },

  ready(){
    console.log("Vue loaded");
  }
});
