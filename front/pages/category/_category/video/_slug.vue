<template>
  <div class="container">
    <div>
      <nuxt-link to="/"><Logo /></nuxt-link>
      <div>
        <video controls autoplay>
          <source :src="video.path" />
        </video>
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
    let video = (await app.$axios.get(`${app.$axios.defaults.baseURL}/category/${params.category}/video/${params.slug}`)).data;

    return { video };
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

video {
  width: 100%;
}
</style>
