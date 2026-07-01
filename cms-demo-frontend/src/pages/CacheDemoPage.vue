<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '../composable/useApi'
const api = useApi(); const result = ref<any>(null); const error = ref('')
async function loadSummary() { error.value = ''; try { const response = await api.get('/api/cache-demo/summary'); result.value = response.data } catch (err: any) { error.value = err.response?.data?.message || 'Cache demo failed.' } }
async function clearCache() { await api.post('/api/cache-demo/clear'); await loadSummary() }
</script>
<template><section><p class="eyebrow">Cache Flow</p><h1>Cached Summary</h1><div class="toolbar"><button @click="loadSummary">Load Summary</button><button class="secondary" @click="clearCache">Clear Cache</button></div><p v-if="error" class="alert error">{{ error }}</p><pre v-if="result">{{ JSON.stringify(result, null, 2) }}</pre></section></template>
