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

        let date = [];
        let nav = [];

        for(let i of value[0]){
          date.unshift(i.date);
          nav.unshift(i.nav);
        }

        console.log(value[0]);

        let ctx = document.getElementById('chart');
        let myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: date,
            datasets: [{
              label: this.symbol,
              data: nav
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false
          }
        });
      });
    }
  },

  ready(){
    console.log("Vue loaded");
  }
});
