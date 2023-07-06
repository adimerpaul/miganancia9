<template>
    <q-page class="q-pa-xs">
      <div class="row">
        <div class="col-12">
          <q-form @submit.prevent="saleProduct">
          <div class="row">
            <div class="col-6 col-md-5">
              <q-input v-model="date.dateIni" label="Fecha Inicial" type="date" color="primary" dense outlined
              :rules="[val => val !== null || 'Seleccione una fecha', val => val <= date.dateFin || 'La fecha inicial debe ser menor a la fecha final']"/>
            </div>
            <div class="col-6 col-md-5">
              <q-input v-model="date.dateFin" label="Fecha Final" type="date" color="primary" dense outlined
               :rules="[val => val !== null || 'Seleccione una fecha', val => val >= date.dateIni || 'La fecha final debe ser mayor a la fecha inicial']"/>
            </div>
            <div class="col-12 col-md-2 flex flex-center">
              <q-btn :loading="loading" type="submit" label="Buscar" color="primary" dense outlined no-caps icon="search"/>
            </div>
          </div>
          </q-form>
        </div>
        <div class="col-12 col-md-6">
          <PieChart :chartOptions="chartOptions"/>
        </div>
        <div class="col-12 col-md-6">
          <PieChart :chartOptions="chartOptions2"/>
        </div>
      </div>
    </q-page>
</template>

<script>
import PieChart from 'components/PieChart.vue'
import moment from 'moment'

export default {
  components: {
    PieChart
  },
  data () {
    return {
      loading: false,
      chartOptions: {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
        },
        title: {
          text: 'Productos Vendidos',
          align: 'center'
        },
        subtitle: {
          text: `del ${moment().startOf('month').format('DD/MM/YYYY')} al ${moment().endOf('month').format('DD/MM/YYYY')}`
        },
        tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
          point: {
            valueSuffix: '%'
          }
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
          }
        },
        series: [
          {
            name: 'Producto',
            colorByPoint: true,
            data: []
          }
        ]
      },
      chartOptions2: {
        chart: {
          type: 'column'
        },
        title: {
          align: 'left',
          text: 'Productos Vendidos'
        },
        subtitle: {
          align: 'left',
          text: `del ${moment().startOf('month').format('DD/MM/YYYY')} al ${moment().endOf('month').format('DD/MM/YYYY')}`
        },
        accessibility: {
          announceNewData: {
            enabled: true
          }
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
          title: {
            text: 'Total por producto'
          }
        },
        legend: {
          enabled: false
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            dataLabels: {
              enabled: true,
              format: '{point.y:.1f}U'
            }
          }
        },
        tooltip: {
          headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}U</b> del total<br/>'
        },
        series: [
          {
            name: 'Producto',
            colorByPoint: true,
            data: []
          }
        ]
      },
      datos: [],
      date: {
        dateIni: moment().startOf('month').format('YYYY-MM-DD'),
        dateFin: moment().endOf('month').format('YYYY-MM-DD')
      }
    }
  },
  created () {
    this.saleProduct()
  },
  methods: {
    formatDMY (date) {
      return moment(date).format('DD/MM/YYYY')
    },
    saleProduct () {
      this.loading = true
      this.$axios.post('saleProduct', this.date).then(response => {
        this.chartOptions.subtitle = `del ${this.formatDMY(this.date.dateIni)} al ${this.formatDMY(this.date.dateFin)}`
        this.chartOptions2.subtitle = `del ${this.formatDMY(this.date.dateIni)} al ${this.formatDMY(this.date.dateFin)}`
        this.datos = []
        response.data.forEach(element => {
          this.datos.push({
            name: element.product,
            y: parseFloat(element.quantity)
          })
        })
        this.chartOptions.series[0].data = this.datos
        this.chartOptions2.series[0].data = this.datos
      }).catch(error => {
        this.$alert.error(error.response.data.message)
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
