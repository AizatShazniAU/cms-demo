<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useApi } from '../composable/useApi'
import { useUserSession } from '../stores/userSession'
const api = useApi(); const router = useRouter(); const session = useUserSession()
const email = ref('demo@example.test'); const password = ref('password'); const loading = ref(false); const error = ref('')
async function login() { loading.value = true; error.value = ''; try { const response = await api.post('/api/login', { email: email.value, password: password.value }); session.setSession(response.data.data.token, response.data.data.user); router.push('/dashboard') } catch (err: any) { error.value = err.response?.data?.message || 'Login failed.' } finally { loading.value = false } }
</script>
<template><section class="login-page"><form class="login-card" @submit.prevent="login"><p class="eyebrow">Portfolio Demo</p><h1>CMS Demo Login</h1><p class="muted">Token-style authentication with safe demo credentials.</p><label>Email<input v-model="email" type="email" /></label><label>Password<input v-model="password" type="password" /></label><p v-if="error" class="alert error">{{ error }}</p><button :disabled="loading">{{ loading ? 'Signing in...' : 'Sign In' }}</button><p class="hint">demo@example.test / password</p></form></section></template>
