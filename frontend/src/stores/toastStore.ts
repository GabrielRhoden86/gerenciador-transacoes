import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastStore = defineStore('toast', () => {
  const show = ref(<boolean>false)
  const message = ref(<string>'')
  const color = ref(<string>'error')

  function showMessage(msg: string, col: string = 'error') {
    message.value = msg
    color.value = col
    show.value = true
  }

  return { show, message, color, showMessage }
})
