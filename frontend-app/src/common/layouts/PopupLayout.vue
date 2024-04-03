<template>
    <header id="main-header" class="p-4 flex flex-row justify-between items-center">
        <slot name="header-left-icon">
            <div class="w-8 h-8"></div>
        </slot>
        <div data-e2e-page-title class="text-base font-semibold text-primary-text">
            <slot name="header-popup-title" />
        </div>
        <slot name="header-right-icon">
            <div class="w-8 h-8"></div>
        </slot>
    </header>
    <main id="main-content" class="px-4 pb-4">
        <slot name="main-content" />
    </main>
</template>
