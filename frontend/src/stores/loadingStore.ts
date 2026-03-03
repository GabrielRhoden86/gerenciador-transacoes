import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoadingStore = defineStore('loading', () => {
  const isLoading = ref<boolean>(false)
  const globalLoading = ref<boolean>(false)
  return { isLoading, globalLoading }
})
