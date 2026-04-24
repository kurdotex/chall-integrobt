import { defineStore } from 'pinia';

interface ModalState {
  isOpen: boolean;
  title: string;
  message: string;
  onConfirm: (() => void) | null;
}

export const useModalStore = defineStore('modal', {
  state: (): ModalState => ({
    isOpen: false,
    title: '',
    message: '',
    onConfirm: null
  }),
  actions: {
    confirm(title: string, message: string, callback: () => void) {
      this.title = title;
      this.message = message;
      this.onConfirm = callback;
      this.isOpen = true;
    },
    close() {
      this.isOpen = false;
      this.onConfirm = null;
    }
  }
});
