<template>
  <q-layout view="lHh Lpr lFf">
    <q-header
      class=""
    >
      <q-toolbar>
<!--        <q-btn-->
<!--          flat-->
<!--          dense-->
<!--          round-->
<!--          icon="menu"-->
<!--          aria-label="Menu"-->
<!--          @click="leftDrawerOpen = !leftDrawerOpen"-->
<!--        />-->
        <q-toolbar-title>
          <div class="text-bold text-center" style="line-height: 1">
            <div>{{ agencia.nombre}}</div>
            <div class="text-subtitle2"><q-icon name="o_place"/>{{agencia.direccion}}</div>
          </div>
        </q-toolbar-title>
        <div>
          <q-btn
            icon="o_shopping_cart"
            :color="cantidadTotalPedida==0?'grey-4':'yellow-7'"
            :disable="cantidadTotalPedida==0"
            text-color="black"
            class="text-bold"
            :label="cantidadTotalPedida"
            @click="saleDialogClick"
          />
        </div>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <q-page class="bg-grey-2 q-pa-xs">
        <div class="row">
          <div class="col-11 col-md-6 bg-white">
            <q-input outlined v-model="search" label="Buscar producto" dense clearable
                     @update:model-value="productsGet" debounce="500" :loading="loading">
              <template v-slot:prepend>
                <q-icon name="search" class="cursor-pointer" />
              </template>
            </q-input>
          </div>
          <div class="col-8 col-md-5">
            <q-select class="bg-white" label="Ordenar" dense outlined v-model="order"
                      :options="orders" map-options emit-value
                      option-value="value" option-label="label"
                      @update:model-value="productsGet"
                      :loading="loading"
            />
          </div>
          <div class="col-4 col-md-1 flex flex-center">
            <q-btn :loading="loading" icon="refresh" dense color="grey" flat @click="productsGet">
              <q-tooltip>Actualizar</q-tooltip>
            </q-btn>
            <q-btn :loading="loading" icon="o_delete" dense color="red" flat @click="clearSearch">
              <q-tooltip>Limpiar</q-tooltip>
            </q-btn>
          </div>
          <div class="col-12 flex flex-center">
            <q-pagination
              v-if="total>60"
              v-model="current_page"
              :max="last_page"
              :max-pages="6"
              boundary-numbers
              @update:model-value="productsGet"
            />
          </div>
          <div class="col-12">
            <q-card flat>
              <q-card-section :class="$q.screen.lt.md?'q-pa-xs':'q-pa-md'">
                <div class="row cursor-pointer" v-if="products.length>0">
                  <div class="col-4 col-md-2" v-for="p in products" :key="p.id">
                    <q-card  flat :class="$q.screen.lt.md?'q-pa-xs':'q-pa-sm'" style="border-radius: 10px;">
                      <q-img @click="clickDetalleProducto(p)" :src="p.image.includes('http')?p.image:`${$url}../images/${p.image}`" :ratio="1">
                        <div class="absolute-bottom text-center text-subtitle2" style="padding: 0px;">
                          {{p.name}}
                        </div>
                        <q-tooltip>{{p.name}}</q-tooltip>
                      </q-img>
                      <q-card-section class="q-pa-none q-ma-none">
                        <div class="text-center text-subtitle2 text-bold">{{ p.price }} Bs</div>
                        <q-btn v-if="p.cantidadPedida==0" @click="moreCantidad(p)" dense flat  class="full-width" icon="o_add_circle_outline" color="grey" no-caps label="Agregar">
                          <q-tooltip>Comprar</q-tooltip>
                        </q-btn>
                        <q-input v-else v-model="p.cantidadPedida" dense input-class="text-center"
                                 mask="#" fill-mask="0" reverse-fill-mask outlined
                        >
                          <template v-slot:prepend>
                            <q-icon :name="p.cantidadPedida==1?'o_delete':'o_remove_circle_outline'" :color="p.cantidadPedida==1?'red':''" class="cursor-pointer" flat @click="minusCantidad(p)" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="o_add_circle_outline" class="cursor-pointer" flat @click="moreCantidad(p)" />
                          </template>
                        </q-input>
<!--                        <pre>{{p}}</pre>-->
                      </q-card-section>
                    </q-card>
                  </div>
                </div>
                <q-card v-else>
                  <q-card-section>
                    <div class="row">
                      <div class="col-12 flex flex-center">
                        <q-avatar size="150px" font-size="150px" color="white" text-color="grey" icon="view_in_ar" />
                      </div>
                      <div class="col-12">
                        <div class="text-bold text-grey text-center">No encontramos productos para tu búsqueda.</div>
                        <div class="text-bold text-grey text-center">Intenta con otra palabra o agrega productos a tu Inventario.</div>
                      </div>
                    </div>
                  </q-card-section>
                </q-card>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </q-page>
    </q-page-container>
    <q-dialog v-model="productDialog" position="right" maximized>
      <q-card style="width: 500px; max-width: 80vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle2 text-bold text-grey">
            {{ productAction === 'create' ? 'Nuevo producto' :
            productAction === 'edit' ? 'Editar producto' : productAction === 'ver' ? 'Detalle de producto' : 'Comprar de producto' }}
          </div>
          <q-space/>
          <q-btn icon="o_highlight_off" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form v-if="productAction === 'ver'">
            <div class="flex flex-center">
              <q-img :src="product.image.includes('http')?product.image:`${$url}../images/${product.image}`" width="200px">
                <div class="absolute-bottom text-center text-subtitle2" style="padding: 0px 0px;">
                  {{product.name}}
                </div>
              </q-img>
            </div>
            <q-card-section class="q-pa-none q-ma-none">
              <div class="text-center text-subtitle2">{{ product.price }} Bs</div>
              <div :class="product.cantidad<=0?'text-center text-bold text-red':' text-center text-bold'">{{ product.cantidad }} Disponible</div>
            </q-card-section>
            <q-card flat bordered class="bg-grey-1">
              <q-card-section>
                <div class="row">
                  <!--                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">-->
                  <!--                      <q-icon name="o_qr_code_2" class="text-grey" size="20px" />-->
                  <!--                      Codigo de barras-->
                  <!--                  </div>-->
                  <!--                  <div class="col-12 col-md-6">-->
                  <!--                    <div class="text-grey text-caption text-right">{{ product.barra }}</div>-->
                  <!--                  </div>-->
                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">
                    <q-icon name="o_paid" class="text-grey" size="20px" />
                    Precio
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="text-grey text-caption text-right">{{ product.price }} Bs</div>
                  </div>
                  <!--                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">-->
                  <!--                    <q-icon name="o_local_shipping" class="text-grey" size="20px" />-->
                  <!--                    Agencia-->
                  <!--                  </div>-->
                  <!--                  <div class="col-12 col-md-6">-->
                  <!--                    <div class="text-grey text-caption text-right" v-if="product.agencia">{{ product.agencia.nombre }}</div>-->
                  <!--                  </div>-->
                  <!--                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">-->
                  <!--                      <q-icon name="o_shopping_cart" class="text-grey" size="20px" />-->
                  <!--                      Categoria-->
                  <!--                  </div>-->
                  <!--                  <div class="col-12 col-md-6">-->
                  <!--                    <div class="text-grey text-caption text-right" v-if="product.category">{{ product.category.name }}</div>-->
                  <!--                  </div>-->
                </div>
              </q-card-section>
            </q-card>
            <div class="row">
              <div class="col-12 text-subtitle2 text-bold">
                Descripción
              </div>
              <div class="col-12 text-grey q-pa-xs">
                {{ product.description}}
              </div>
<!--              <div class="col-12">-->
<!--                <q-btn :loading="loading" icon="o_shopping_cart" label="Comprar" rounded dense color="green" @click="clickbuy(product)" no-caps class="full-width q-mt-xs" />-->
<!--              </div>-->
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="saleDialog" position="right" maximized>
      <q-card style="width: 500px; max-width: 80vw;">
        <q-card-section>
          <div class="row items-center">
            <div class="text-subtitle2 text-bold text-grey">
              Resumen del pedido
            </div>
            <q-space/>
            <q-btn icon="o_highlight_off" flat round dense v-close-popup />
          </div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="saleSubmit">
            <label class="text-subtitle2 text-bold">Datos del cliente</label>
            <q-input v-model="sale.nombre" label="Nombre" dense outlined :rules="[val => !!val || 'Este campo es requerido']" />
            <label class="text-subtitle2 text-bold">Dirección de entrega</label>
            <q-input v-model="sale.direccion" label="Dirección" dense outlined :rules="[val => !!val || 'Este campo es requerido']" />
            <label class="text-subtitle2 text-bold">Notas Adicionales</label>
            <q-input v-model="sale.nota" label="Nota" dense outlined type="textarea" />
            <label class="text-subtitle2 text-bold">Detalle del pedido</label>
<!--            <q-scroll-area class="full-width" style="height: 200px">-->
              <q-list bordered v-if="saleDetails.length>0" style="padding: 0px;margin: 0px;">
<!--                <div class="row" v-for="(saleDetail,i) in saleDetails" :key="i">-->
                  <q-item v-for="(saleDetail,i) in saleDetails" :key="i" class="q-my-sm" dense clickable v-ripple style="padding: 0px;margin: 0px;">
                    <q-item-section top avatar>
                      <q-avatar rounded>
                        <q-img :src="saleDetail.image.includes('http')?saleDetail.image:`${$url}../images/${saleDetail.image}`"
                               class="q-ma-xs" style="border-radius: 5px;"/>
                      </q-avatar>
                    </q-item-section>

                    <q-item-section>
                      <q-item-label caption class="text-black text-bold">{{ saleDetail.name }}</q-item-label>
                      <q-item-label caption lines="1">{{ saleDetail.price }}</q-item-label>
                    </q-item-section>

                    <q-item-section side>
                      <q-input style="width: 100px" v-model="saleDetail.cantidad" dense input-class="text-center"
                               mask="#" fill-mask="0" reverse-fill-mask outlined
                      >
                        <template v-slot:prepend>
                          <q-icon :name="saleDetail.cantidad==1?'o_delete':'o_remove_circle_outline'" :color="saleDetail.cantidad==1?'red':''" class="cursor-pointer" flat @click="minusCantidadDetail(saleDetail)" />
                        </template>
                        <template v-slot:append>
                          <q-icon name="o_add_circle_outline" class="cursor-pointer" flat @click="moreCantidadDetail(saleDetail)" />
                        </template>
                      </q-input>
                    </q-item-section>
                  </q-item>
<!--                </div>-->
              </q-list>
              <div v-else class="text-center q-pa-lg text-bold text-grey">No hay productos</div>
<!--              <div class="fit row wrap justify-between">-->
<!--                <div class="row wrap">-->
<!--                  <q-img :src="product.image.includes('http')?product.image:`${$url}../images/${product.image}`"-->
<!--                         width="40px" class="q-ma-xs" style="border-radius: 5px;"/>-->
<!--                  <div>-->
<!--                    <div class="text-bold">{{product.name}}</div>-->
<!--                    <div>{{product.price}} Bs</div>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div>cantidad</div>-->
<!--              </div>-->
<!--            </q-scroll-area>-->
<!--            <div><pre>{{saleDetails}}</pre></div>-->
            <q-btn rounded class="q-mt-xs full-width text-bold" no-caps
                   color="yellow-7" text-color="black" v-if="saleDetails.length>0"
                   align="around" type="submit">
              <div class="fit row wrap justify-between">
                <div><q-chip color="white" dense :label="cantidadTotalPedida" text-color="black" icon="o_shopping_cart" /></div>
                <div class="flex flex-center">Confirmar</div>
                <div class="flex flex-center">Bs {{ totalVenta }}</div>
              </div>
            </q-btn>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
<!--    v-if="saleDetails.length>0"-->
      <q-btn rounded class="btn-fixed-width full-width text-bold" no-caps
             style="position: fixed; bottom: 20px; right: 0px; z-index: 100;"
             color="yellow-7" text-color="black" v-if="saleDetails.length>0"
             align="around" @click="saleDialogClick" >
          <div class="fit row wrap justify-between">
          <div><q-chip color="white" rounded :label="cantidadTotalPedida" text-color="black" icon="o_shopping_cart" /></div>
          <div class="flex flex-center">Ir al carrito de compras</div>
          <div class="flex flex-center">Bs {{ totalVenta }}</div>
        </div>
      </q-btn>
  </q-layout>
</template>
<script>
import { setCssVar } from 'quasar'

export default {
  data () {
    return {
      user: {},
      current_page: 1,
      total: 0,
      search: '',
      last_page: 1,
      agencia: {},
      columnsProductosVenta: [
        { label: 'borrar', field: 'borrar', name: 'borrar', align: 'left' },
        { label: 'nombre', field: 'nombre', name: 'nombre', align: 'left' },
        { label: 'cantidadVenta', field: 'cantidadVenta', name: 'cantidadVenta' }
      ],
      loading: false,
      leftDrawerOpen: false,
      order: 'id',
      products: [],
      product: {},
      productDialog: false,
      saleDialog: false,
      productAction: '',
      saleDetails: [],
      sale: {},
      orders: [
        { label: 'Ordenar por', value: 'id' },
        { label: 'Menor precio', value: 'price asc' },
        { label: 'Mayor precio', value: 'price desc' },
        // { label: 'Menor cantidad', value: 'cantidad asc' },
        // { label: 'Mayor cantidad', value: 'cantidad desc' },
        { label: 'Orden alfabetico', value: 'name asc' }
      ]
    }
  },
  created () {
    this.$q.loading.show()
    this.$axios.get('agenciaWebSearch/' + this.$route.params.tienda).then(res => {
      this.agencia = res.data
      this.productsGet()
      setCssVar('primary', this.agencia.color)
    }).catch(err => {
      this.$alert.error(err.response.data.message)
    }).finally(() => {
      this.$q.loading.hide()
    })
  },
  methods: {
    moreCantidadDetail (product) {
      product.cantidad++
      const find = this.products.find(p => p.id === product.id)
      if (find) {
        find.cantidadPedida++
      }
    },
    minusCantidadDetail (product) {
      if (product.cantidad > 0) {
        product.cantidad--
        const find = this.products.find(p => p.id === product.id)
        if (find) {
          if (find.cantidadPedida > 0) {
            find.cantidadPedida--
          }
        }
        if (product.cantidad <= 0) {
          const index = this.saleDetails.findIndex(p => p.id === product.id)
          this.saleDetails.splice(index, 1)
        }
      }
    },
    saleSubmit () {
      console.log(this.sale)
    },
    saleDialogClick () {
      this.sale = {
        nombre: '',
        direccion: '',
        nota: ''
      }
      this.saleDialog = true
    },
    minusCantidad (product) {
      if (product.cantidadPedida > 0) {
        product.cantidadPedida--
        const find = this.saleDetails.find(p => p.id === product.id)
        if (find) {
          find.cantidad--
          if (find.cantidad <= 0) {
            const index = this.saleDetails.findIndex(p => p.id === product.id)
            this.saleDetails.splice(index, 1)
          }
        }
      }
    },
    moreCantidad (product) {
      product.cantidadPedida++
      const find = this.saleDetails.find(p => p.id === product.id)
      if (find) {
        find.cantidad++
      } else {
        this.saleDetails.push({
          id: product.id,
          cantidad: 1,
          price: product.price,
          name: product.name,
          image: product.image
        })
      }
    },
    clickDetalleProducto (product) {
      this.product = product
      this.productDialog = true
      this.productAction = 'ver'
    },
    clearSearch () {
      this.$q.dialog({
        title: 'Limpiar busqueda',
        message: '¿Estas seguro de limpiar la busqueda?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.search = ''
        this.saleDetails = []
        this.productsGet()
      })
    },
    productsGet () {
      this.loading = true
      this.$axios.get(`agenciaProducts?page=${this.current_page}&search=${this.search}&order=${this.order}&category=${this.category}&agencia_id=${this.agencia.id}`).then(res => {
        this.products = []
        res.data.products.data.forEach(product => {
          product.cantidadPedida = 0
          this.products.push(product)
        })
        this.last_page = res.data.products.last_page
        this.current_page = res.data.products.current_page
        this.total = res.data.products.total
      }).catch(err => {
        this.$alert.error(err.response.data.message)
      }).finally(() => {
        this.loading = false
      })
    }
  },
  computed: {
    cantidadTotalPedida () {
      let total = 0
      this.saleDetails.forEach(product => {
        total += product.cantidad
      })
      return total
    },
    totalVenta () {
      let total = 0
      this.saleDetails.forEach(product => {
        total += product.price * product.cantidad
      })
      const totalVenta = Math.round(total * 100) / 100
      return totalVenta
    }
  }
}
</script>
