<template>
  <video controls autoplay @ended="handleEnded">
    <source :src="video.path" />
  </video>
</template>

<script>
export default {
  asyncData: async ({ app, params }) => {
    const token = localStorage.getItem('token');

    if (!token) {
      app.context.redirect('/login');
    } else {
      try {
        let video = (await app.$axios.get(`${app.$axios.defaults.baseURL}/category/${params.category}/video/${params.slug}`, { headers: { Authorization: `Bearer ${token}` } })).data;

        return { video };
      } catch (e) {
        if (e.response.status === 401) {
          app.context.redirect('/login');
        } else {
          throw e;
        }
      }
    }
  },
  methods: {
    handleEnded() {
      this.$router.push(`/category/${this.$route.params.category}`);
    }
  }
}
</script>

<style scoped>
video {
  width: 100%;
  height: 100%;
}
</style>
