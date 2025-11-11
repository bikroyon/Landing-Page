import { ref } from 'vue'

interface Toast {
  id: number
  title: string
  description: string
  type: 'success' | 'error' | 'info'
}

const toasts = ref<Toast[]>([])
let idCounter = 0

// Correctly load the sound
const notificationSound = new Audio('/sounds/notification.mp3')

export function useToast() {
  function showToast(title: string, description: string, type: Toast['type']) {
    const id = ++idCounter
    toasts.value.push({ id, title, description, type })

    // Play notification sound
    notificationSound.play().catch(() => {
      console.warn('Notification sound failed to play. User interaction may be required.')
    })

    // Auto-remove after 3s
    setTimeout(() => {
      toasts.value = toasts.value.filter((t) => t.id !== id)
    }, 6000)
  }

  return { toasts, showToast }
}
