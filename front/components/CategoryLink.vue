<template>
  <nuxt-link :to="`category/${category.slug}`">
    <img v-if="!!category.picture" :src="category.picture" :alt="category.name" /><br>
    {{ category.name }}
  </nuxt-link>
</template>

<script>
export default {
  props: {
    category: Object
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
  user-drag: none;
  -webkit-user-drag: none;
  user-select: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}

a:hover {
  background-color: #F0F0F0;
}

img {
  width: 100%;
}
</style>
