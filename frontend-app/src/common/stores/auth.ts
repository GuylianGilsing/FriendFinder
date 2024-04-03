import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAuthStore = defineStore('auth', () => {
    // State
    const userID = ref<string | null>(null);

    // Getters
    const getUserID = computed(() => userID);

    // Actions
    function setUserID(id: string) {
        userID.value = id;
    }

    return {
        getUserID,

        setUserID,
    };
});
