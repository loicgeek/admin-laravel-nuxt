<template>
  <div id="main-body" class="leading-normal tracking-normal">
    <div class="flex ">
      <side-bar />

      <div id="main-content" class="w-full bg-gray-100 pl-0 lg:pl-64 min-h-screen" :class="sideBarOpen ? 'overlay' : ''">
        <!-- <header-bar /> -->

        <div class="p-6 bg-gray-100 mb-20">
          <Nuxt />
        </div>

        <!-- <Footer /> -->
      </div>
    </div>
  </div>
</template>

<style>
html,body {
  @apply bg-gray-100 text-gray-600
}
</style>
