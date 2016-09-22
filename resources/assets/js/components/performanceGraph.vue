<template>
  <div class="chartContainer">
    <div class="cardHeader">Fund Performance</div>
    <div class="loader" v-show="isLoading" transition="fade">
      <div class="loadCircle loadCircleBig">
        <div class="loadCircle loadCircleSmall"></div>
      </div>
    </div>
    <div class="chartControl">
      <div class="controlSelect" @click="updateMode(false)" :class="{active: useRawNav===false}">Return</div>
      <div class="controlSelect" @click="updateMode(true)" :class="{active: useRawNav===true}">NAV</div>
      <div class="separator">|</div>
      <div class="controlSelect" @click="updateInterval(1)" :class="{active: numberOfMonths===1}">1m</div>
      <div class="controlSelect" @click="updateInterval(2)" :class="{active: numberOfMonths===2}">2m</div>
      <div class="controlSelect" @click="updateInterval(3)" :class="{active: numberOfMonths===3}">3m</div>
      <div class="controlSelect" @click="updateInterval(6)" :class="{active: numberOfMonths===6}">6m</div>
      <div class="controlSelect" @click="updateInterval(12)" :class="{active: numberOfMonths===12}">1y</div>
    </div>
    <div class="chartWrapper">
      <canvas id="chart" width="100" height="300"></canvas>
    </div>
  </div>
</template>

<script>
window.Navi = require('../naviAPI.js');
window.Vars = require('../vars.js');

export default {
  data() { return {
    isLoading: false,
    funds: [],
    useRawNav: false,
    numberOfMonths: 1,
    chart: {},
    chartObject: {},
    chartOptions: {},
    datasets: []
  }},

  methods: {
    updateInterval(interval){
      this.numberOfMonths = interval;
      this.$emit('updateChart', this.funds);
    },

    updateMode(mode){
      this.useRawNav = mode;
      this.$emit('updateChart', this.funds);
    }
  },

  ready() {
    Chart.defaults.global.defaultFontColor = '#FFFFFF';
    Chart.defaults.global.defaultFontFamily = 'Roboto';
    Chart.defaults.global.defaultFontSize = 14;

    this.chartObject = document.getElementById('chart');
    this.chartOptions = Vars.historicalLineChartOptions;
    this.chart = new Chart(this.chartObject, {
      type: 'line', data: { datasets: [] },
      options: this.chartOptions
    });
  },

  events: {
    'updateChart'(funds){
      this.funds = funds;

      let promises = [];
      for(let fund of funds){
        if(fund === '') continue;
        promises.push(Navi.getHistoricalChartData(
          fund,
          this.numberOfMonths,
          this.useRawNav
        ));
      }

      this.isLoading = true;

      Promise.all(promises).then(values => {
        this.datasets = [];
        for(let value of values){
          this.datasets.push({
            label: value.name,
            data: value.chartData,
            borderColor: value.fundData.color,
            fill: false, tension: 0
          });
        }

        this.chartOptions.scales.yAxes[0].scaleLabel.labelString = this.useRawNav
          ? 'NAV (THB)' : 'Return (%)';

        this.chart.destroy();
        this.chart = new Chart(this.chartObject, {
          type: 'line', data: { datasets: this.datasets },
          options: this.chartOptions
        });

        this.isLoading = false;
      });
    }
  }
};
</script>

<style lang="scss">
@import "../../sass/variables.scss";

.chartContainer{
  @include style-card;
  box-sizing: border-box;
  padding: 20px 40px 20px 20px;
  position: relative;
  width: 100%;
}

.loader{
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  @include center-everything;
  background: $grey;
}

.loadCircle{
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.24);
  border-radius: 50%;
  animation: pulse 1s $bounce-timing infinite;
  @include transition-normal;
}

.loadCircleBig{
  width: 80px;
  height: 80px;
  @include center-everything;
  animation-delay: 0.2s;
}

.chartControl{
  margin-bottom: 10px;
  position: absolute;
  top: 20px; right: 30px;
  font-size: 0.8rem;
}

.controlSelect{
  display: inline-block;
  padding: 2px 5px;
  border: 2px rgba(0, 0, 0, 0) solid;
  border-radius: 2px;
  box-sizing: border-box;
  cursor: pointer;
  @include transition-normal;
}

.controlSelect.active{
  border: 2px $accent solid;
}

.separator{
  display: inline-block;
  margin: 0 10px;
}


.chartWrapper{
  width: 100%;
  height: 300px;
}
</style>
