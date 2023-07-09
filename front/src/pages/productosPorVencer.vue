<template>
  <q-page class="bg-grey-2 q-pa-xs">
    <div class="row">
      <div class="col-12">
        <q-table dense :rows-per-page-options="[0]" :rows="buys" :filter="filter" :columns="buysColumns" title="Productos por vencer">
          <template v-slot:top-right>
            <q-btn flat rounded dense icon="refresh" @click="getBuys" color="primary" />
            <q-input outlined v-model="filter" label="Buscar" dense>
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body-cell-id="props">
            <q-td :props="props" auto-width>
              {{ props.pageIndex+1}}
            </q-td>
          </template>
          <template v-slot:body-cell-days="props">
            <q-td :props="props" auto-width>
              <q-chip dense class="text-white" :color="props.row.days <= 15 ? 'red' : 'green'">
                {{ props.row.days }} días
              </q-chip>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
  </q-page>
</template>
<script>
export default {
  data () {
    return {
      filter: '',
      buysColumns: [
        { name: 'id', label: '#', field: 'id', align: 'center', sortable: true },
        { name: 'description', label: 'Descripción', field: 'description', align: 'left', sortable: true },
        { name: 'days', label: 'Días para vencer', field: 'days', align: 'center', sortable: true },
        { name: 'dateExpiration', label: 'Fecha de vencimiento', field: 'dateExpiration', align: 'center', sortable: true },
        { name: 'product', label: 'Producto', field: (row) => row.product == null ? 'No aplica' : row.product.name, align: 'left', sortable: true },
        { name: 'quantity', label: 'Cantidad', field: 'quantity', align: 'center', sortable: true },
        { name: 'user', label: 'Usuario', field: (row) => row.user.name, align: 'left', sortable: true }
        // { name: 'price', label: 'Precio', field: 'price', align: 'center', sortable: true },
        // { name: 'total', label: 'Total', field: 'total', align: 'center', sortable: true },
        // { name: 'date', label: 'Fecha', field: 'date', align: 'center', sortable: true },
        // { name: 'actions', label: 'Acciones', field: 'actions', align: 'center' }
        // { name: 'date', label: 'Fecha', field: 'date', align: 'center', sortable: true },
        // { name: 'actions', label: 'Acciones', field: 'actions', align: 'center' }
      ],
      buys: []
    }
  },
  methods: {
    getBuys () {
      this.$axios.get('buys')
        .then((response) => {
          this.buys = response.data
        })
        .catch((error) => {
          console.log(error)
        })
    }
  },
  mounted () {
    this.getBuys()
  }
}
</script>
