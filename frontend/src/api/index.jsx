import axios from 'axios';

const api = axios.create({
  timeout: 10000,
  baseURL: process.env.NODE_ENV === 'production'
    ? '/planium/backend'
    : 'http://planium' 
})

export default api;