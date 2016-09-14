export default {
  historicalLineChartOptions: {
    responsive: true, maintainAspectRatio: false,
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
        scaleLabel: {
          display: true, labelString: 'Return (%)'
        },
        ticks: {
          callback: (label, i, arr) => i % Math.ceil(arr.length/5) === 0 || label==0 ? label.toFixed(2) : null
        },
        gridLines: { color: 'rgba(255,255,255,0.24)', zeroLineWidth: 2, zeroLineColor: '#FFFFFF' }
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
}
