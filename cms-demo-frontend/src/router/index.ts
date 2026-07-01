import { createRouter, createWebHistory } from 'vue-router'
import { useUserSession } from '../stores/userSession'
import LoginPage from '../pages/LoginPage.vue'
import DashboardPage from '../pages/DashboardPage.vue'
import ApiDemoPage from '../pages/ApiDemoPage.vue'
import UploadPage from '../pages/UploadPage.vue'
import ReportPage from '../pages/ReportPage.vue'
import ActivityLogsPage from '../pages/ActivityLogsPage.vue'
import CacheDemoPage from '../pages/CacheDemoPage.vue'
export const router = createRouter({ history: createWebHistory(), routes: [
  { path: '/', redirect: '/dashboard' },
  { path: '/login', component: LoginPage },
  { path: '/dashboard', component: DashboardPage, meta: { requiresAuth: true } },
  { path: '/api-demo', component: ApiDemoPage, meta: { requiresAuth: true } },
  { path: '/upload', component: UploadPage, meta: { requiresAuth: true } },
  { path: '/reports', component: ReportPage, meta: { requiresAuth: true } },
  { path: '/activity-logs', component: ActivityLogsPage, meta: { requiresAuth: true } },
  { path: '/cache-demo', component: CacheDemoPage, meta: { requiresAuth: true } },
]})
router.beforeEach((to) => { const session = useUserSession(); if (to.meta.requiresAuth && !session.isLoggedIn) return '/login'; if (to.path === '/login' && session.isLoggedIn) return '/dashboard' })
