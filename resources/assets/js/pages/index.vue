<template>
  <div class="routerWrapper">
    <div class="section">
      <fund-card name="kt-st" color="#2196F3"></fund-card>
      <fund-card name="k-fixed" color="#FF5722"></fund-card>
      <fund-card name="k-cbond" color="#E91E63"></fund-card>
      <fund-card name="tmbabf" color="#8BC34A"></fund-card>
    </div>

    <div class="section">
      <div width="400px" height="400px">
        <canvas id="chart" width="300" height="400"></canvas>
      </div>
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
      Navi.getHistoricalChartData('k-fixed'),
      Navi.getHistoricalChartData('k-cbond'),
      Navi.getHistoricalChartData('tmbabf')
    ]).then(values => {
      console.log(values);

      let ctx = document.getElementById('chart');
      let myChart = new Chart(ctx, {
        type: 'line',
        data: { datasets: [{
          label: 'kt-st',
          data: values[0],
          fill: false,
          borderColor: '#2196F3'
        },{
          label: 'k-fixed',
          data: values[1],
          fill: false,
          borderColor: '#FF5722'
        },{
          label: 'k-cbond',
          data: values[2],
          fill: false,
          borderColor: '#E91E63'
        },{
          label: 'tmbabf',
          data: values[3],
          fill: false,
          borderColor: '#8BC34A'
        }]},
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            xAxes: [{
              type: 'time',
              time: {
                unit: 'day',
                displayFormats: { day: 'DD/MM/YY' }
              },
              ticks: { callback(label, index, arr){
                return index % Math.ceil(arr.length/10) === 0 ? label : null;
              }}
            }],
            yAxes: [{
              ticks: { callback(label, index, arr){
                return index % Math.ceil(arr.length/5) === 0 ? label.toFixed(4) : null;
              }}
            }]
          },
          legend: { display: false }
        }
      });
    });
  }
}
</script>

<style lang="sass">

</style>
