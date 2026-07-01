<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUserSession } from '../stores/userSession'
const router = useRouter()
const session = useUserSession()
function logout() { session.logout(); router.push('/login') }
</script>
<template>
  <aside class="sidebar">
    <div class="brand">CMS Demo</div>
    <nav>
      <RouterLink to="/dashboard">Dashboard</RouterLink>
      <RouterLink to="/api-demo">API Demo</RouterLink>
      <RouterLink to="/upload">Upload</RouterLink>
      <RouterLink to="/reports">Reports</RouterLink>
      <RouterLink to="/activity-logs">Activity Logs</RouterLink>
      <RouterLink to="/cache-demo">Cache</RouterLink>
    </nav>
    <div class="sidebar-footer"><div>{{ session.user?.name }}</div><small>{{ session.user?.role }}</small><button class="secondary" @click="logout">Logout</button></div>
  </aside>
</template>
