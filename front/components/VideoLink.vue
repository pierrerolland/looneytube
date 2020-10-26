<template>
  <nuxt-link :to="`/category/${categorySlug}/video/${video.slug}`">
    <img v-if="!!video.picture" :src="video.picture" :alt="video.name" /><br>
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
  display: block;
  font-size: 1rem;
  color: black;
  text-decoration: none;
  box-shadow: #AAAAAA 2px 1px 4px;
  padding: 1rem;
  background-color: #FEFEFE;
}

a:hover {
  background-color: #F0F0F0;
}

img {
  width: 100%;
}
</style>
