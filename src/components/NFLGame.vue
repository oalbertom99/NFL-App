<style>
.carousel-control-prev,
.carousel-control-next {
  width: 20%; 
}

.carousel-control-prev-icon,
.carousel-control-next-icon,
.bi-caret-left-square,
.bi-caret-right-square {
  color: #000000; 
  background-color: rgba(0, 0, 0, 0);
  border-radius: 50%;
  padding: 0.2rem;
}

</style>
<template>
  <div class="nfl-results">
    <h1>Hola Alberto</h1>
  </div>
</template>

<script>
export default {
  name: 'NFLResults',
  data() {
    return {};
  },
  computed: {
    gameId: function() {      
      return this.$route.params.gameId;
    }
  },  
  created: function(){    
  },
  mounted(){    
  },
  methods: {

  }
};
</script>

<style scoped>
.nfl-results {
  text-align: center;
}
</style>
