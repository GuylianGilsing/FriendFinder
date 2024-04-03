<template>
    <main id="main-content" class="p-4 flex-grow flex flex-col justify-center items-center gap-16 relative overflow-y-auto text-secondary-text bg-primary">
        <h1 class="text-4xl font-semibold">Friend Finder</h1>
        <div class="w-full max-w-[156px] flex flex-col gap-4">
            <button @click="login" type="button" class="w-full py-2 px-4 block border text-primary-text border-secondary-text bg-secondary-text">Sign in</button>
            <button @click="register" type="button" class="w-full py-2 px-4 block border border-secondary-text">Sign up</button>
        </div>
    </main>
</template>

<script setup lang="ts">
import { keycloak } from '@/auth';

async function login() {
    await keycloak.login();
}

async function register() {
    await keycloak.register();
}
</script>
