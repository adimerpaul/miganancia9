<template>
  <div>
    <highcharts
      :options="chartOptions"
    ></highcharts>
  </div>
</template>

<script>
import Highcharts from 'highcharts'
import exportingInit from 'highcharts/modules/exporting'
import stockInit from 'highcharts/modules/stock'
import offlineExporting from 'highcharts/modules/offline-exporting'

stockInit(Highcharts)
exportingInit(Highcharts)
offlineExporting(Highcharts)

export default {
  data () {
    return {
    }
  },
  props: {
    chartOptions: {
      type: Object,
      default: () => {}
    }
  }
}
</script>
