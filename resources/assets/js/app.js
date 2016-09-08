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
    
  },

  methods: {

  },

  ready(){
    console.log("Vue loaded");

    Promise.all([
      Navi.getHistoricalChartData('kt-st'),
      Navi.getHistoricalChartData('ktplus'),
      Navi.getHistoricalChartData('k-fixed')
    ]).then(values => {
      console.log(values);

      let ctx = document.getElementById('chart');
      let myChart = new Chart(ctx, {
        type: 'line',
        data: {
          datasets: [{
            label: 'kt-st',
            data: values[0],
            fill: false,
            borderColor: '#2196F3'
          },{
            label: 'ktplus',
            data: values[1],
            fill: false,
            borderColor: '#8BC34A'
          },{
            label: 'k-fixed',
            data: values[2],
            fill: false,
            borderColor: '#FF5722'
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
});
