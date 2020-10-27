<template>
  <div class="grid-5 has-gutter categories">
    <category-link
      v-for="category in categories"
      :key="category.slug"
      :category="category"
    />
  </div>
</template>

<script>
import CategoryLink from '@/components/CategoryLink';

export default {
  components: { CategoryLink },
  async asyncData({ app }) {
    const token = localStorage.getItem('token');

    if (!token) {
      app.context.redirect('/login');
    } else {
      try {
        let categories = (await app.$axios.get(`${app.$axios.defaults.baseURL}/categories`, { headers: { Authorization: `Bearer ${token}` } })).data;

        return { categories };
      } catch (e) {
        if (e.response.status === 401) {
          app.context.redirect('/login');
        } else {
          throw e;
        }
      }
    }
  }
}
</script>

<style scoped>
.categories {
  padding: 2rem;
}
</style>
