import { defineStore } from 'pinia'
export interface DemoUser { id: number; name: string; email: string; role: string }
export const useUserSession = defineStore('userSession', {
  state: () => ({ token: localStorage.getItem('demo_token') || '', user: JSON.parse(localStorage.getItem('demo_user') || 'null') as DemoUser | null }),
  getters: { isLoggedIn: (state) => Boolean(state.token) },
  actions: {
    setSession(token: string, user: DemoUser) { this.token = token; this.user = user; localStorage.setItem('demo_token', token); localStorage.setItem('demo_user', JSON.stringify(user)) },
    logout() { this.token = ''; this.user = null; localStorage.removeItem('demo_token'); localStorage.removeItem('demo_user') },
  },
})
