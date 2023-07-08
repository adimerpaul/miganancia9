<template>
  <q-page class="q-pa-md bg-grey-2">
    <q-table
      flat bordered dense
      title="Creacion de menu"
      :rows="products"
      row-key="name"
      selection="multiple"
      :loading="loading"
      :columns="[
        { name: 'order', label: 'Order', field: 'order', align: 'center', sortable: true },
        { name: 'name', label: 'Name', field: 'name', align: 'letf', sortable: true },
        { name: 'price', label: 'Price', field: 'price', align: 'center', sortable: true },
        { name: 'description', label: 'Description', field: 'description', align: 'left', sortable: true },
        { name: 'image', label: 'Image', field: 'image', sortable: true }
      ]"
      :filter="filter"
      v-model:selected="selected"
      :rows-per-page-options="[0]"
    >
      <template v-slot:top-right>
        <q-btn icon="print" label="Imprimir Menu" dense color="primary" no-caps v-if="selected.length>0" @click="printMenu" />
        <q-btn icon="refresh" @click="getProducts" flat round dense :loading="loading" />
        <q-input
          v-model="filter"
          outlined
          dense
          debounce="300"
          placeholder="Buscar"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-order="props">
        <q-td :props="props" auto-width>
          <q-select
            outlined
            v-model="props.row.order"
            :options="orders"
            emit-value
            map-options
            dense
            style="width: 50px"></q-select>
        </q-td>
      </template>
      <template v-slot:body-cell-name="props">
        <q-td :props="props" auto-width>
          <div class="text-bold">{{ props.row.name }}</div>
        </q-td>
      </template>
      <template v-slot:body-cell-description="props">
        <q-td :props="props" auto-width>
          <div style="max-width: 300px;width: 300px;white-space: normal;line-height: 1" class="text-justify">
              {{ props.row.description }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-image="props">
        <q-td :props="props" auto-width>
          <q-img :src="props.row.image.includes('http')?props.row.image:`${$url}../images/${props.row.image}`" :ratio="1">
          </q-img>
        </q-td>
      </template>
    </q-table>

<!--    <div class="q-mt-md">-->
<!--      Selected: {{ JSON.stringify(selected) }}-->
<!--    </div>-->
  </q-page>
</template>

<script>
import { Imprimir } from 'src/addons/Imprimir'

export default {
  data () {
    return {
      filter: '',
      products: [],
      selected: [],
      orders: [],
      loading: false
    }
  },
  created () {
    for (let i = 1; i <= 100; i++) {
      this.orders.push(i)
    }
    this.getProducts()
  },
  methods: {
    ordenar (selected) {
      selected.sort((a, b) => {
        return a.order - b.order
      })
    },
    async printMenu () {
      const ordenado = this.selected.sort((a, b) => {
        return a.order - b.order
      })
      await Imprimir.menuPrint(ordenado)
    },
    getProducts () {
      this.loading = true
      this.$axios.get('productAllBase64')
        .then((response) => {
          this.products = []
          response.data.forEach((item, index) => {
            item.order = index + 1
            this.products.push(item)
          })
          // console.log(response.data)
        })
        .catch((error) => {
          console.log(error)
        }).finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
