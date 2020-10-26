<template>
  <div class="container">
    <div>
      <nuxt-link to="/"><Logo /></nuxt-link>
      <div class="grid-5 has-gutter videos">
        <video-link
          v-for="video in category.videos"
          :key="video.slug"
          :video="video"
          :category-slug="category.slug"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Logo from '@/components/Logo';
import CategoryLink from '@/components/CategoryLink';
import VideoLink from '@/components/VideoLink';

export default {
  components: {VideoLink, CategoryLink, Logo},
  asyncData: async ({ app, params }) => {
    let category = (await app.$axios.get(`${app.$axios.defaults.baseURL}/category/${params.slug}`)).data;

    return { category };
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

.videos {
  padding: 2rem;
}
</style>
