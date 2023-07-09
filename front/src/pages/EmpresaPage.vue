<template>
  <q-page class="bg-grey-2 q-pa-xs">
    <div class="row">
      <div class="col-12">
        <q-table dense :rows-per-page-options="[0]" :rows="agencias" :columns="agenciasColumns" :filter="filter" title="Lista de empresa registradas">
          <template v-slot:top-right>
            <q-btn dense icon="add_circle_outline" @click="agenciaAdd" color="primary" label="Agregar" no-caps />
            <q-btn flat rounded dense icon="refresh" @click="getBuys" color="primary" />
            <q-input outlined v-model="filter" label="Buscar" dense>
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
          <template v-slot:body-cell-actions="props">
            <q-td :props="props" auto-width>
              <q-btn dense rounded flat icon="edit" @click="edit(props.row)" color="primary" />
            </q-td>
          </template>
          <template v-slot:body-cell-id="props">
            <q-td :props="props" auto-width>
              {{ props.pageIndex+1}}
            </q-td>
          </template>
          <template v-slot:body-cell-nombre="props">
            <q-td :props="props" auto-width>
              <div class="text-bold">{{props.row.nombre}}</div>
            </q-td>
          </template>
          <template v-slot:body-cell-logo="props">
            <q-td :props="props" auto-width>
              <q-img :src="props.row.logo.includes('http')?props.row.logo:`${$url}../images/${props.row.logo}`" width="50px" />
            </q-td>
          </template>
          <template v-slot:body-cell-color="props">
            <q-td :props="props" auto-width>
              <q-badge :color="props.row.color" text-color="white">{{props.row.color}}</q-badge>
            </q-td>
          </template>
        </q-table>
        <pre>{{agencias}}</pre>
      </div>
    </div>
    <q-dialog v-model="agenciaDialog" position="right" maximized>
      <q-card style="width: 500px; max-width: 80vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle2 text-bold text-grey">
            {{ agenciaAction === 'create' ? 'Nuevo Agencia' : agenciaAction === 'edit' ? 'Editar Agencia' : 'Ver Agencia' }}
          </div>
          <q-space/>
          <q-btn icon="o_highlight_off" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form v-if="agenciaAction === 'ver'">
            <div class="flex flex-center">
              <q-img :src="agencia.image.includes('http')?agencia.image:`${$url}../images/${agencia.image}`" width="200px">
                <div class="absolute-bottom text-center text-subtitle2" style="padding: 0px 0px;">
                  {{agencia.name}}
                </div>
              </q-img>
            </div>
            <q-card-section class="q-pa-none q-ma-none">
              <div class="text-center text-subtitle2">{{ agencia.price }} Bs</div>
              <div :class="agencia.cantidad<=0?'text-center text-bold text-red':' text-center text-bold'">{{ agencia.cantidad }} Disponible</div>
            </q-card-section>
            <q-card flat bordered class="bg-grey-1">
              <q-card-section>
                <div class="row">
                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">
                    <q-icon name="o_paid" class="text-grey" size="20px" />
                    Precio
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="text-grey text-caption text-right">{{ agencia.price }} Bs</div>
                  </div>
                  <div class="col-12 col-md-6 text-subtitle2 text-bold text-grey">
                    <q-icon name="o_shopping_bag" class="text-grey" size="20px" />
                    Costo
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="text-grey text-caption text-right">{{ agencia.cost }} Bs</div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
            <div class="row">
              <div class="col-12 text-subtitle2 text-bold">
                Descripción
              </div>
              <div class="col-12 text-grey q-pa-xs">
                {{ agencia.description}}
              </div>
              <div class="col-6">
                <q-btn :loading="loading" icon="o_delete" label="Eliminar" rounded dense color="red" @click="agenciaDelete" no-caps class="full-width" />
              </div>
              <div class="col-6">
                <q-btn :loading="loading" icon="o_edit" label="Editar" outline rounded dense color="grey" @click="agenciaAction = 'edit'" no-caps class="full-width" />
              </div>
              <div class="col-12">
                <q-btn :loading="loading" icon="o_shopping_cart" label="Comprar" rounded dense color="green" @click="clickbuy(agencia)" no-caps class="full-width q-mt-xs" />
              </div>
            </div>
          </q-form>
          <q-form @submit="agenciaSave" v-if="agenciaAction === 'create' || agenciaAction === 'edit'">
            <div class="flex flex-center">
              <q-uploader
                accept=".jpg, .png"
                multiple
                auto-upload
                label="Arrastra una imagen o haz click para seleccionar"
                @uploading="uploadingFn"
                @failed="errorFn"
                ref="uploader"
                max-files="1"
                auto-expand
                :url="`${$url}upload/${agencia.id}/fileCreate`"
                stack-label="upload image"/>
            </div>
            <div class="text-grey text-caption">Te recomendamos que la imagen tenga un tamaño de 500 x 500 px en formato PNG y pese máximo 2MB.</div>
            <q-input outlined v-model="agencia.name" label="Nombre del Empresa*" dense hint="Recuerda, este debe ser único en tu inventario" :rules="[val => !!val || 'Este campo es requerido']" />
<!--            <q-input outlined v-model="agencia.amount" label="Cantidad" input-class="text-center" dense hint="">-->
<!--              <template v-slot:append>-->
<!--                <q-icon name="o_add_circle_outline" @click="cantidadMore" class="cursor-pointer"/>-->
<!--              </template>-->
<!--              <template v-slot:prepend>-->
<!--                <q-icon name="o_remove_circle_outline" @click="cantidadMinus" class="cursor-pointer"/>-->
<!--              </template>-->
<!--            </q-input>-->
            <q-input outlined v-model="agencia.direccion" label="Dirección*" dense hint="Dirección del Empresa" :rules="[val => !!val || 'Este campo es requerido']"/>
            <q-input outlined v-model="agencia.telefono" label="Teléfono*" dense hint="Teléfono del Empresa" :rules="[val => !!val || 'Este campo es requerido']"/>
<!--            <q-input type="textarea" outlined v-model="agencia.description" label="Descripción" dense hint="Agrega una descripción del agenciao"/>-->
            <q-input outlined v-model="agencia.website" label="Sitio Web" dense hint="Sitio Web del Empresa"/>
            <div class="text-center">
              <q-badge color="grey-3" text-color="black" class="q-mb-sm">
                {{ agencia.color }}
              </q-badge>
              <q-color
                v-model="agencia.color"
                no-header
                no-footer
                default-view="palette"
                class="my-picker"
              />
            </div>
            <q-btn class="full-width" rounded
                   :color="!agencia.name || !agencia.price ? 'grey' : 'green'"
                   :disable="!agencia.name || !agencia.price"
                   label="Guardar" no-caps type="submit" :loading="loading"/>
          </q-form>
          <q-form @submit="buySave" v-if="agenciaAction === 'buy'">
            <q-select proveedor dense outlined v-model="buy.client_id" label="Proveedor"
                      map-options emit-value option-value="id" option-label="name"
                      :options="proveedores" hint="Proveedor del gasto">
              <template v-slot:after>
                <q-btn icon="add_circle_outline" color="green" flat round dense @click="providerCreate" />
              </template>
            </q-select>
            <q-select proveedor dense outlined v-model="buy.agencia_id" label="Producto"
                      map-options emit-value option-value="id" option-label="name"
                      :options="agenciasAll" hint="Producto que compras">
            </q-select>
            <q-select proveedor dense outlined v-model="buy.unit" label="Unidad"
                      :options="['Unidad', 'Caja', 'Paquete', 'Kilo', 'Litro']" hint="Unidad de medida">
            </q-select>
            <q-input outlined v-model="buy.amount" label="Cantidad" input-class="text-center" dense hint="">
              <template v-slot:append>
                <q-icon name="o_add_circle_outline" @click="cantidadMoreBuy" class="cursor-pointer"/>
              </template>
              <template v-slot:prepend>
                <q-icon name="o_remove_circle_outline" @click="cantidadMinusBuy" class="cursor-pointer"/>
              </template>
            </q-input>
            <q-input outlined v-model="buy.dateExpiration" label="Fecha de vencimiento" dense hint="Fecha de vencimiento del agenciao" type="date"/>
            <q-input outlined type="textarea" v-model="buy.description" label="Descripción" dense hint="Descripción de la compra"/>
            <q-btn class="full-width" rounded
                   :color="buy.amount==0 ? 'grey' : 'green'"
                   :disable="buy.amount==0?true:false"
                   label="Guardar" no-caps type="submit" :loading="loading"/>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
export default {
  data () {
    return {
      agenciaDialog: false,
      filter: '',
      loading: false,
      agenciaAction: 'create',
      agenciasColumns: [
        { name: 'actions', label: 'Acciones', field: 'actions', align: 'center' },
        { name: 'id', label: 'ID', field: 'id', align: 'center', sortable: true },
        { name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left', sortable: true },
        { name: 'direccion', label: 'Dirección', field: 'direccion', align: 'left', sortable: true },
        { name: 'telefono', label: 'Teléfono', field: 'telefono', align: 'left', sortable: true },
        { name: 'web', label: 'Web', field: 'web', align: 'left', sortable: true },
        { name: 'logo', label: 'Logo', field: 'logo', align: 'left', sortable: true },
        { name: 'color', label: 'Color', field: 'color', align: 'left', sortable: true }
      ],
      agencias: [],
      agencia: {
        id: '',
        nombre: '',
        direccion: '',
        telefono: '',
        web: '',
        logo: '',
        color: ''
      }
    }
  },
  methods: {
    agenciaSave () {
      this.loading = true
      if (this.agenciaAction === 'create') {
        this.$axios.post('agencias', this.agencia)
          .then((response) => {
            this.agencias.push(response.data)
            this.agenciaDialog = false
            this.$alert.success('Agencia creada correctamente')
          })
          .catch((error) => {
            this.$alert.error(error)
          })
          .finally(() => {
            this.loading = false
          })
      } else {
        this.$axios.put('agencias/' + this.agencia.id, this.agencia)
          .then((response) => {
            this.agencias[this.agencias.findIndex((agencia) => agencia.id === this.agencia.id)] = response.data
            this.agenciaDialog = false
            this.$alert.success('Agencia actualizada correctamente')
          })
          .catch((error) => {
            this.$alert.error(error)
          })
          .finally(() => {
            this.loading = false
          })
      }
    },
    uploadingFn (e) {
      e.xhr.onload = () => {
        if (e.xhr.readyState === e.xhr.DONE) {
          if (e.xhr.status === 200) {
            // this.dialogPhoto=false
            this.product.image = e.xhr.response
          }
        }
      }
    },
    errorFn (err) {
      console.log(err)
      this.$alert.error('Error al subir la imagen, intente nuevamente el nombre no debe contener espacios o ñ')
    },
    agenciaAdd () {
      this.agenciaAction = 'create'
      this.agenciaDialog = true
      this.agencia = {
        id: '',
        nombre: '',
        direccion: '',
        telefono: '',
        web: '',
        logo: '',
        color: '#0052a3'
      }
    },
    getBuys () {
      this.$axios.get('agencias')
        .then((response) => {
          this.agencias = response.data
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
