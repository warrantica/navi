/*==============================================================================
Bootstrap Vue, jQuery, API and injecting requests with CSRF tokens
==============================================================================*/

require('./bootstrap');
window.Navi = require('./naviAPI.js');

/*==============================================================================
Components
==============================================================================*/

Vue.component('fund-card', require('./components/fundCard.vue'));

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
      Navi.getHistoricalNav(this.symbol).then(value => {
        console.log(value);

        let date = [];
        let nav = [];

        for(let i of value[0]){
          date.unshift(moment(i.date, 'D/M/YYYY'));
          nav.unshift(i.nav);
        }

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
            maintainAspectRatio: false,
            scales: {
              xAxes: [{
                type: 'time',
                time: {
                  unit: 'day',
                  displayFormats: { day: 'DD/MM/YY' }
                }
              }]
            }
          }
        });
      });
    }
  },

  ready(){
    console.log("Vue loaded");
  }
});
