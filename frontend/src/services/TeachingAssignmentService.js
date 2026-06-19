import api from '@/plugins/axios'

export const getTeachingAssignments = async () => {
  const response = await api.get(
    '/teaching-assignments'
  )

  return response.data
}

export const createTeachingAssignment = async (
  data
) => {
  const response = await api.post(
    '/teaching-assignments',
    data
  )

  return response.data
}

export const updateTeachingAssignment = async (
  id,
  data
) => {
  const response = await api.put(
    `/teaching-assignments/${id}`,
    data
  )

  return response.data
}

export const deleteTeachingAssignment = async (
  id
) => {
  const response = await api.delete(
    `/teaching-assignments/${id}`
  )

  return response.data
}