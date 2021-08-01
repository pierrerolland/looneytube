<template>
  <div>
    <div class="container">
      <div>
        <h1 v-if="$route.name === 'index'" class="title">
          <Logo />
        </h1>
        <nuxt-link v-else to="/" class="title">
          <Logo />
        </nuxt-link>
        <Nuxt />
      </div>
    </div>
  </div>
</template>

<script>
import Logo from '@/components/Logo';

export default {
  components: { Logo }
}
</script>


<style>
html {
  font-family:
    'Source Sans Pro',
    -apple-system,
    BlinkMacSystemFont,
    'Segoe UI',
    Roboto,
    'Helvetica Neue',
    Arial,
    sans-serif;
  font-size: 16px;
  word-spacing: 1px;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  box-sizing: border-box;
}

*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
}

.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  text-align: center;
}

img {
  height: auto;
}
</style>
