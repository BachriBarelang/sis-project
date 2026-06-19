import dayjs from 'dayjs'
import 'dayjs/locale/id'

dayjs.locale('id')

export const formatDate = (date) => {
  if (!date) return '-'

  return dayjs(date).format('DD MMMM YYYY')
}

export const formatDateTime = (date) => {
  if (!date) return '-'

  return dayjs(date).format('DD MMMM YYYY HH:mm')
}