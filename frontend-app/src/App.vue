<template>
    <RouterView />
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { RouterView } from 'vue-router';
import { keycloak } from './auth';
import { useAuthStore } from './common/stores/auth';

const authStore = useAuthStore();

onMounted(() => {
    if (keycloak.tokenParsed !== undefined && keycloak.tokenParsed.sub !== undefined) {
        authStore.setUserID(keycloak.tokenParsed.sub);
    }
});
</script>
