import { defineStore } from 'pinia';

export const useBranchStore = defineStore('branch', {
  state: () => ({
    searchQuery: '',
    currentPage: 1
  }),
  actions: {
    setSearch(query: string) {
      this.searchQuery = query;
      this.currentPage = 1; 
    },
    setPage(page: number) {
      this.currentPage = page;
    }
  }
});
