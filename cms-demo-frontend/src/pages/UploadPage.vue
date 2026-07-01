<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '../composable/useApi'
const api = useApi(); const selectedFile = ref<File | null>(null); const loading = ref(false); const error = ref(''); const result = ref<any>(null)
function onFileChange(event: Event) { selectedFile.value = (event.target as HTMLInputElement).files?.[0] || null }
async function upload() { if (!selectedFile.value) { error.value = 'Please choose a file first.'; return } loading.value = true; error.value = ''; result.value = null; const form = new FormData(); form.append('document', selectedFile.value); try { const response = await api.post('/api/uploads', form); result.value = response.data.data } catch (err: any) { error.value = err.response?.data?.message || 'Upload failed.' } finally { loading.value = false } }
</script>
<template><section><p class="eyebrow">Import Flow</p><h1>Upload Document</h1><div class="panel"><input type="file" accept=".pdf,.csv,.xlsx,.txt" @change="onFileChange" /><button :disabled="loading" @click="upload">{{ loading ? 'Uploading...' : 'Upload' }}</button><p class="muted">Allowed: PDF, CSV, XLSX, TXT. Max: 2 MB.</p></div><p v-if="error" class="alert error">{{ error }}</p><pre v-if="result">{{ JSON.stringify(result, null, 2) }}</pre></section></template>
