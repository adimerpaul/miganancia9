import LoginPage from 'pages/LoginPage.vue'
import MainLayout from 'layouts/MainLayout.vue'
import IndexPage from 'pages/IndexPage.vue'
import ProductosPage from 'pages/ProductosPage.vue'
import SalePage from 'pages/SalePage.vue'
import ProductosPorVencer from 'pages/productosPorVencer.vue'
import EstadisticasPage from 'pages/EstadisticasPage.vue'
import PedidoLayout from 'layouts/PedidoLayout.vue'
import MenuPage from 'pages/MenuPage.vue'
import EmpresaPage from 'pages/EmpresaPage.vue'

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: IndexPage, meta: { requiresAuth: true } },
      { path: 'productos', component: ProductosPage, meta: { requiresAuth: true } },
      { path: 'sale', component: SalePage, meta: { requiresAuth: true } },
      { path: 'productosPorVencer', component: ProductosPorVencer, meta: { requiresAuth: true } },
      { path: 'estadisticas', component: EstadisticasPage, meta: { requiresAuth: true } },
      { path: 'menu', component: MenuPage, meta: { requiresAuth: true } },
      { path: 'empresa', component: EmpresaPage, meta: { requiresAuth: true } }
    ]
  },
  { path: '/:tienda', component: PedidoLayout },
  {
    path: '/login',
    component: LoginPage
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
