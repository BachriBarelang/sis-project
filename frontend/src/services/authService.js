import api from './api'

export const loginUser = async (payload) => {
  const response = await api.post('/login', payload)

  return response.data
}

export const getProfile = async () => {
  const response = await api.get('/me')

  return response.data
}

