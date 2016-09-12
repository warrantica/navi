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
          <span class="timeControl" @click="updateChart(1)">1m</span>
          <span class="timeControl" @click="updateChart(2)">2m</span>
          <span class="timeControl" @click="updateChart(3)">3m</span>
          <span class="timeControl" @click="updateChart(6)">6m</span>
          <span class="timeControl" @click="updateChart(12)">1y</span>
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
window.Vars = require('../vars.js');

export default{
  data(){ return{
    fundData: [],
    chart: {},
    chartObject: {},
    chartOptions: {},
    datasets: []
  }},

  methods: {
    updateChart(numberOfMonths){
      let promises = [];
      for(let fund of this.fundData){
        promises.push(Navi.getHistoricalChartData(fund.name, numberOfMonths));
      }

      Promise.all(promises).then(values => {
        this.datasets = [];
        for(let value of values){
          this.datasets.push({
            label: value.name,
            data: value.chartData,
            fill: false,
            borderColor: value.fundData.color,
            tension: 0
          });
        }

        this.chart.destroy();
        this.chart = new Chart(this.chartObject, {
          type: 'line', data: { datasets: this.datasets },
          options: this.chartOptions
        });
      });
    }
  },

  ready(){
    Chart.defaults.global.defaultFontColor = '#FFFFFF';
    Chart.defaults.global.defaultFontFamily = 'Roboto';
    Chart.defaults.global.defaultFontSize = 14;

    this.chartObject = document.getElementById('chart');
    this.chartOptions = Vars.historicalLineChartOptions;
    this.chart = new Chart(this.chartObject, {
      type: 'line', data: { datasets: [] },
      options: this.chartOptions
    });

    Navi.getAllFunds().then(data => {
      this.fundData = data;
      this.updateChart(1);
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
