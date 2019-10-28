<template>
  <v-app>
    <Header></Header>
    <v-content>
      <router-view/>
    </v-content>
    <Footer></Footer>
  </v-app>
</template>

<script>
import Header from './components/NavComponent.vue'
import Footer from './components/FooterComponent.vue'
export default {
  components: {
    Header,
    Footer
  }
}
</script>
