<template>
  <div class="routerWrapper">
    <fund-card name="kt-st" color="#2196F3"></fund-card>
    <fund-card name="ktplus" color="#8BC34A"></fund-card>
    <fund-card name="k-fixed" color="#FF5722"></fund-card>

    <div width="400px" height="500px">
      <canvas id="chart" width="300" height="300"></canvas>
    </div>
  </div>
</template>

<script>
window.Navi = require('../naviAPI.js');

export default{
  methods: {

  },

  ready(){
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
          scales: { xAxes: [{
            type: 'time',
            time: {
              unit: 'day',
              displayFormats: { day: 'DD/MM/YY' }
            },
            ticks: { callback(label, index, arr){
              return index % Math.ceil(arr.length/20) === 0 ? label : null;
            }}
          }]},
          legend: { display: false }
        }
      });
    });
  }
}
</script>

<style lang="sass">

</style>
