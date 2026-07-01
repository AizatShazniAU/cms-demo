<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useApi } from '../composable/useApi'
const api = useApi(); const logs = ref<any[]>([]); const error = ref('')
async function loadLogs() { try { const response = await api.get('/api/activity-logs'); logs.value = response.data.data } catch (err: any) { error.value = err.response?.data?.message || 'Could not load logs.' } }
onMounted(loadLogs)
</script>
<template><section><p class="eyebrow">Audit Flow</p><h1>Activity Logs</h1><button @click="loadLogs">Refresh</button><p v-if="error" class="alert error">{{ error }}</p><table><thead><tr><th>Time</th><th>User</th><th>Method</th><th>Endpoint</th></tr></thead><tbody><tr v-for="log in logs" :key="log.id"><td>{{ log.timestamp }}</td><td>{{ log.email }}</td><td>{{ log.method }}</td><td>{{ log.endpoint }}</td></tr></tbody></table></section></template>
