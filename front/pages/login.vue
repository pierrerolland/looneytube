<template>
  <form class="login" @submit.prevent="submit">
    <div>
      <input v-model="username" type="text" placeholder="Username" />
    </div>
    <div>
      <input v-model="password" type="password" placeholder="Password" />
    </div>
    <div>
      <input type="submit" />
    </div>
  </form>
</template>

<script>
export default {
  data: () => ({
    username: '',
    password: ''
  }),
  mounted() {
    if (localStorage.getItem('token')) {
      localStorage.removeItem('token');
    }
  },
  methods: {
    async submit() {
      const formData = new FormData();
      formData.append('_username', this.username);
      formData.append('_password', this.password);

      const { token } = (await this.$axios.post(`${this.$axios.defaults.baseURL}/login`, formData)).data;

      console.log(token);
      if (token) {
        localStorage.setItem('token', token);
        this.$router.push('/');
      }
    }
  }
}
</script>

<style scoped>
input {
  padding: 0.5rem;
  margin: 0.2rem;
}
</style>
