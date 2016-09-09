<template>
  <div class="routerWrapper">
    <div class="section">
      <!-- <fund-card name="tmbabf" color="#8BC34A"></fund-card> -->
      <fund-card v-for="fund in fundData" :name="fund.name" :color="fund.color"></fund-card>
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
  data(){ return{
    fundData: []
  }},

  methods: {

  },

  ready(){

    let chartPromise = Navi.getAllFunds().then(data => {
      this.fundData = data;
      console.log(this.fundData);

      let promises = [];
      for(let fund of this.fundData){
        promises.push(Navi.getHistoricalChartData(fund.name));
      }

      return promises;
    }).then(promises => {
      //There's gotta be a better way than to call then on an array of promises
      //just to all() them again...
      Promise.all(promises).then(values => {
        console.log(values);

        let datasets = [];
        for(let value of values){
          datasets.push({
            label: value.name,
            data: value.chartData,
            fill: false,
            borderColor: value.fundData.color
          });
        }

        let ctx = document.getElementById('chart');
        let myChart = new Chart(ctx, {
          type: 'line', data: { datasets },
          options: {
            responsive: true, maintainAspectRatio: false,
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
              yAxes: [{ ticks: { callback(label, index, arr){
                return index % Math.ceil(arr.length/5) === 0 ? label.toFixed(4) : null;
              }}}]
            },
            legend: { display: false }
          }
        });
      });
    });
  }
}
</script>

<style lang="sass">

</style>
