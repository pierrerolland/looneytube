<template>
  <div class="grid-5 has-gutter videos">
    <video-link
      v-for="video in category.videos"
      :key="video.slug"
      :video="video"
      :category-slug="category.slug"
    />
  </div>
</template>

<script>
import CategoryLink from '@/components/CategoryLink';
import VideoLink from '@/components/VideoLink';

export default {
  components: { VideoLink, CategoryLink },
  asyncData: async ({ app, params }) => {
    const token = localStorage.getItem('token');

    if (!token) {
      app.context.redirect('/login');
    } else {
      try {
        let category = (await app.$axios.get(`${app.$axios.defaults.baseURL}/category/${params.slug}`, { headers: { Authorization: `Bearer ${token}` } })).data;

        return {category};
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
.videos {
  padding: 2rem;
}
</style>
