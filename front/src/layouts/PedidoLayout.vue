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
            color="grey-4"
            text-color="grey"
            label="0"
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
          <div class="col-10 col-md-5">
            <q-select class="bg-white" label="Ordenar" dense outlined v-model="order"
                      :options="orders" map-options emit-value
                      option-value="value" option-label="label"
                      @update:model-value="productsGet"
                      :loading="loading"
            />
          </div>
          <div class="col-2 col-md-1 flex flex-center">
            <q-btn :loading="loading" icon="refresh" dense color="grey-7" flat @click="productsGet">
              <q-tooltip>Actualizar</q-tooltip>
            </q-btn>
          </div>
          <div class="col-12 flex flex-center">
            <q-pagination
              v-model="current_page"
              :max="last_page"
              :max-pages="6"
              boundary-numbers
              @update:model-value="productsGet"
            />
          </div>
          <div class="col-12">
            <q-card>
              <q-card-section class="q-pa-xs">
                <div class="row cursor-pointer" v-if="products.length>0">
                  <div class="col-4 col-md-2" v-for="p in products" :key="p.id">
                    <q-card  flat bordered :class="$q.screen.lt.md?'q-pa-xs':'q-pa-sm'" style="border-radius: 10px;">
                      <q-img @click="clickDetalleProducto(p)" :src="p.image.includes('http')?p.image:`${$url}../images/${p.image}`" :ratio="1">
                        <div class="absolute-bottom text-center text-subtitle2" style="padding: 0px;">
                          {{p.name}}
                        </div>
                        <q-tooltip>{{p.name}}</q-tooltip>
                      </q-img>
                      <q-card-section class="q-pa-none q-ma-none">
                        <div class="text-center text-subtitle2 text-bold">{{ p.price }} Bs</div>
                        <q-input v-model="p.cantidadPedida" dense input-class="text-center"
                                 mask="#" fill-mask="0" reverse-fill-mask outlined
                        >
                          <template v-slot:prepend>
                            <q-icon name="o_remove_circle_outline" flat />
                          </template>
                          <template v-slot:append>
                            <q-icon name="o_add_circle_outline" flat />
                          </template>
                        </q-input>
                        <pre>{{p}}</pre>
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
                {{ product.descripcion}}
              </div>
              <div class="col-12">
                <q-btn :loading="loading" icon="o_shopping_cart" label="Comprar" rounded dense color="green" @click="clickbuy(product)" no-caps class="full-width q-mt-xs" />
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-layout>
</template>
<script>
import { setCssVar } from 'quasar'

export default {
  data () {
    return {
      user: {},
      current_page: 1,
      search: '',
      last_page: 1,
      agencia: {},
      loading: false,
      leftDrawerOpen: false,
      order: 'id',
      products: [],
      product: {},
      productDialog: false,
      productAction: '',
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
    clickDetalleProducto (product) {
      this.product = product
      this.productDialog = true
      this.productAction = 'ver'
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
      }).catch(err => {
        this.$alert.error(err.response.data.message)
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
