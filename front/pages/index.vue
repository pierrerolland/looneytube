<template>
  <div class="container">
    <div>
      <h1 class="title">
        <Logo />
      </h1>
      <div class="grid-5 has-gutter categories">
        <category-link
          v-for="category in categories"
          :key="category.slug"
          :category="category"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Logo from '@/components/Logo';
import CategoryLink from '@/components/CategoryLink';

export default {
  components: {CategoryLink, Logo},
  async asyncData({ app, process }) {
    console.log(process.env);
    return { categories: [] };
    let categories = (await app.$axios.get(`${app.$axios.defaults.baseURL}/categories`)).data;

    return { categories };
  }
}
</script>

<style scoped>
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  text-align: center;
}

.categories {
  padding: 2rem;
}
</style>
