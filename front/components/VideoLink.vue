<template>
  <nuxt-link :to="`/category/${categorySlug}/video/${video.slug}`" :style="`background-image: url('${video.picture ? video.picture.replace('\'', '\\\'') : ''}');`">
    {{ video.name }}
  </nuxt-link>
</template>

<script>
export default {
  props: {
    categorySlug: String,
    video: Object
  }
}
</script>

<style scoped>
a {
  display: flex;
  min-height: 200px;
  justify-content: center;
  align-items: center;
  font-size: 1rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
  box-shadow: #AAAAAA 2px 1px 4px;
  padding: 1rem;
  background-color: #333333;
  background-size: cover;
  user-drag: none;
  text-shadow: 1px 1px 2px black;
  -webkit-user-drag: none;
  user-select: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}

a:hover {
  border: 2px solid black;
}
</style>
