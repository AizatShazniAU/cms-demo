import axios from 'axios'
import { useUserSession } from '../stores/userSession'
export const api = axios.create({ baseURL: import.meta.env.VITE_API_BASE_URL })
api.interceptors.request.use((config) => {
  const session = useUserSession()
  if (session.token) { config.headers.Authorization = `Bearer ${session.token}` }
  return config
})
export function useApi() { return api }
