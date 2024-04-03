import { defineStore } from "pinia";
import { computed, ref } from "vue";
import type SearchFilter from "@/domains/search/models/SearchFilter";

export const useFiltersStore = defineStore('filters', () => {
    // State
    const availableFilters = ref<SearchFilter[]>([]);
    const defaultFilters = ref<SearchFilter[]>([]);
    const activeFilters = ref<SearchFilter[]>([]);

    // Getters
    const getAvailableFilters = computed(() => availableFilters);
    const getActiveFilters = computed(() => activeFilters);

    // Actions
    function setAvailableFilters(filters: SearchFilter[]): void {
        availableFilters.value = filters;
    }

    function setDefaultFilters(filters: SearchFilter[]): void {
        defaultFilters.value = filters;
    }

    function resetFilters(): void {
        activeFilters.value = defaultFilters.value;
    }

    return {
        getAvailableFilters,
        getActiveFilters,

        setAvailableFilters,
        setDefaultFilters,
        resetFilters,
    };
});
