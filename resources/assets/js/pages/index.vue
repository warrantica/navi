<template>
  <div class="routerWrapper">
    <div class="section fundCardList">
      <!-- <fund-card name="tmbabf" color="#8BC34A"></fund-card> -->
      <fund-card v-for="fund in fundData" :name="fund.name" :color="fund.color"></fund-card>
      <div class="fundCard-newButton" v-link="{path:'/fund/add'}">Add new mutual fund</div>
    </div>

    <div class="section">

      <div class="chartContainer">
        <div class="cardHeader">Fund Performance</div>
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
        promises.push(Navi.getHistoricalChartData(fund.name, 1));
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
            borderColor: value.fundData.color,
            tension: 0
          });
        }

        Chart.defaults.global.defaultFontColor = '#FFFFFF';
        Chart.defaults.global.defaultFontFamily = 'Roboto';
        Chart.defaults.global.defaultFontSize = 14;

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
                gridLines: { color: 'rgba(255,255,255,0.24)', zeroLineWidth: 2, zeroLineColor: '#FFFFFF' }
              }],
              yAxes: [{
                ticks: {
                  callback: (label, i, arr) => i % Math.ceil(arr.length/5) === 0 ? label.toFixed(4) : null
                },
                gridLines: { color: 'rgba(0,0,0,0)', zeroLineWidth: 2, zeroLineColor: '#FFFFFF' }
              }]
            },
            legend: { display: false },
            tooltips: {
              mode: 'x-axis',
              backgroundColor: '#212121',
              bodySpacing: 10,
              bodyFontSize: 16,
              xPadding: 10, yPadding: 10,
              caretSize: 0,
              cornerRadius: 2,
              callbacks: {
                title: (arr,data) => moment(arr[0].xLabel).format('DD/MM/YY'),
                label: (item,data) => '  '+data.datasets[item.datasetIndex].label+': '+item.yLabel.toFixed(4),
                labelColor: (item,ctx) => {
                  return {
                    borderColor: ctx.data.datasets[item.datasetIndex].borderColor,
                    backgroundColor: ctx.data.datasets[item.datasetIndex].borderColor
                  }
                }
              }
            }
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
  margin: 10px;
  display: flex;
  flex-flow: row;
  align-items: center;
}

.chartContainer{
  @include style-card;
  padding: 20px 40px 20px 20px;
  position: relative;
  width: 80%;
}

.chartControl{
  margin-bottom: 10px;
  position: absolute;
  top: 10px; right: 10px;
}

.chartWrapper{
  width: 100%;
  height: 300px;
}
</style>
