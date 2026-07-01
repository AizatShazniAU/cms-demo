<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '../composable/useApi'
const api = useApi(); const loading = ref(false); const error = ref(''); const result = ref<any>(null)
async function callApi(forceError = false) { loading.value = true; error.value = ''; result.value = null; try { const response = await api.get('/api/demo/status', { params: { fail: forceError ? 1 : undefined } }); result.value = response.data } catch (err: any) { error.value = err.response?.data?.message || 'Request failed.' } finally { loading.value = false } }
</script>
<template><section><p class="eyebrow">API Flow</p><h1>Protected API Call</h1><div class="toolbar"><button @click="callApi(false)">Call Success Endpoint</button><button class="secondary" @click="callApi(true)">Force Error State</button></div><p v-if="loading" class="alert">Loading request...</p><p v-if="error" class="alert error">{{ error }}</p><pre v-if="result">{{ JSON.stringify(result, null, 2) }}</pre></section></template>
