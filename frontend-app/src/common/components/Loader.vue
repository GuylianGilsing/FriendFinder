<template>
    <div class="w-8 h-8 mx-auto border-[0.2em] border-divider border-t-primary border-r-primary rounded-full"></div>
</template>

<style scoped lang="scss">
div {
    animation-name: spin;
    animation-duration: 1s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
