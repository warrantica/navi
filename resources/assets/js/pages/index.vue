<template>
  <div class="routerWrapper">
    <div class="section fundCardList">
      <!-- <fund-card name="tmbabf" color="#8BC34A"></fund-card> -->
      <fund-card v-for="fund in fundData" :name="fund.name" :color="fund.color"></fund-card>
      <div class="fundCard-newButton" v-link="{path:'/fund/add'}">Add new mutual fund</div>
    </div>

    <div class="section">

      <div class="chartContainer">
        <div class="chartControl">
          1m 2m 3m 6m 1y
        </div>
        <div class="chartWrapper">
          <canvas id="chart" width="100" height="300"></canvas>
        </div>
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

        Chart.defaults.global.defaultFontColor = '#FFFFFF';
        Chart.defaults.global.defaultFontFamily = 'Roboto';
        Chart.defaults.global.defaultFontSize = 16;

        let ctx = document.getElementById('chart');
        let myChart = new Chart(ctx, {
          type: 'line', data: { datasets },
          options: { responsive: true, maintainAspectRatio: false,
            scales: {
              xAxes: [{
                type: 'time',
                time: {
                  unit: 'day',
                  displayFormats: { day: 'DD/MM/YY' }
                },
                ticks: {
                  callback: (label, i, arr) => i % Math.ceil(arr.length/4) === 0 ? label : null,
                  maxRotation: 0,
                },
                gridLines: { color: 'rgba(255,255,255,0.24)', zeroLineWidth: 3, zeroLineColor: '#FFFFFF' }
              }],
              yAxes: [{
                ticks: {
                  callback: (label, i, arr) => i % Math.ceil(arr.length/5) === 0 ? label.toFixed(4) : null
                },
                gridLines: { color: 'rgba(0,0,0,0)', zeroLineWidth: 3, zeroLineColor: '#FFFFFF' }
              }]
            },
            legend: { display: false }
          }
        });
      });
    });
  }
}
</script>

<style lang="scss">
@import "../../sass/variables.scss";

.fundCardList{
  display: flex;
  flex-flow: row;
  align-items: stretch;
}

.fundCard-newButton{
  @include style-card;
  background: $dark-grey;
  border: 1px $white solid;
  margin: 10px;
  display: flex;
  flex-flow: row;
  align-items: center;
}

.chartContainer{
  @include style-card;
  padding: 10px 40px 20px 20px;
  background: $dark-grey;
}

.chartControl{
  margin-bottom: 10px;
}

.chartWrapper{
  width: 500px;
  height: 300px;
}
</style>
