<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useUserSession } from './stores/userSession'
import AppNav from './components/AppNav.vue'
const route = useRoute()
const session = useUserSession()
const showNav = computed(() => route.path !== '/login' && session.isLoggedIn)
</script>
<template><div class="app-shell" :class="{ 'has-sidebar': showNav }"><AppNav v-if="showNav" /><main class="main-panel"><RouterView /></main></div></template>
