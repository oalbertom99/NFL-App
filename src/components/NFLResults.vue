<template>
    <div class="nfl-results">
      <h1>NFL Regular Season</h1>
      <h2 style="margin-top: 3%; margin-bottom: 3%;">{{ semana_selected }}</h2>
      <div style="display: flex; justify-content: center; align-items: center;">
        <select class="form-select" style="width: 35%;" v-model="semana_selected" @change="getResultsByWeek()">          
          <option v-for="semana in semanas" :key="semana" :value="semana">{{ semana }}</option>
        </select>
      </div>
      <div v-if="loading">Cargando resultados...</div>    
      <div v-else ref="myCarousel" id="carouselExampleAutoplaying" class="carousel slide mt-5" data-bs-ride="carousel">
        <div class="carousel-inner">          
          <div  class="carousel-item"  v-for="(grupo, index) in groupedPartidos"  :key="index" :class="{ active: index === 0 }">
            <div class="row align-items-center col-4 mb-5" v-for="partido in grupo" :key="partido.game.id"  style="border: dotted; border-color: black; max-width: 30%; max-height: 25%; min-width: 30%; min-height: 15%; margin-right: auto; margin-left: auto;" type="button" @click="detallesPartido(partido.game.id)">
              <div class="col-4 text-center">
                <img :src="partido.teams.away.logo" class="img-fluid" alt="...">
                <h6>{{ partido.teams.away.name }}</h6>
              </div>
              <div class="col-4 text-center">
                <h3>VS</h3>
              </div>
              <div class="col-4 text-center">
                <img :src="partido.teams.home.logo" class="img-fluid" alt="...">
                <h6>{{ partido.teams.home.name }}</h6>
              </div>
            </div>
          </div>
        </div>        

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span><i class="bi bi-caret-left-square" style="font-size: 2rem;"></i></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span><i class="bi bi-caret-right-square" style="font-size: 2rem;"></i></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>          
    </div>
  </template>
  
  <script>
  import axios from 'axios';    
  
  export default {
    name: 'NFLResults',
    data() {
      return {
        partidos: [], 
        partidos_filtro: [],
        semanas: [],
        semana_selected: 'Week 1',
        loading: true,
        error: null
      };
    },
    // Es una función que Vue evalúa y guarda en caché automáticamente, solo se vuelve a calcular si alguna de las dependencias que usa ha camiado. Evita recalcular valores si los datos no han cambiado. Es una propiedad reactiva y optimizada que Vue solo recalcula cuando sus dependencias cambian. 
    computed: {

      groupedPartidos: function() {
        const grupos = [];
        for (let i = 0; i < this.partidos_filtro.length; i += 2) {
          grupos.push(this.partidos_filtro.slice(i, i + 2));
        }        
        return grupos;
      }

    },
    // El hook created se ejecuta inmediatamente después de que el componente ha sido instanciado y se han inicializado sus datos (data,computed, methods, etc), esto significa que en created ya puedes acceder y manipular los datos y métodos del componente, aunque el componente aún no se han montado en el DOM.
    created: async function() {   
      await this.obtenerResultadosNFL();   
    },
    // El hook mounted se ejecuta solo una vez, justo después de que el componente ha sido montado en el DOM. Es ideal para tareas que necesitan acceso directo a los elementos HTML del componente, ya que garantiza que el DOM esté listo y disponible.
    mounted: async function(){

    },
    methods: {
      detallesPartido: function(_game_id){              
        this.$router.push({ name: 'Game', params: { gameId: _game_id } });
      },

      getResultsByWeek: async function(){

        // En esta función filtraremos todos los partidos de la temporada regular para obtener únicamente aquellos de la semana seleccionada.        
        this.partidos_filtro = this.partidos.filter((game) => game.game.stage == 'Regular Season' && game.game.week == this.semana_selected); 

      },

      obtenerResultadosNFL: async function(){
        
        // Para obtener la información de los partidos de la temporada regular 2024 de la NFL haremos uso de API-Sports en su versión gratuita con un límite de 100 peticiones diarias.
        try {
          // Función de axios para realizar solicitudes HTTP de tipo GET. await espera a que la solicitud termine para asignar el resultado a response. response almancena la respuesta en caso de ser exitosa.
          const response = await axios.get('https://v1.american-football.api-sports.io/games', {
              // Params es un objeto que contiene parámetros de consulta que se anexan a la url del endpoint. Estos parámetros determinan que datos específicos se quieren solicitar. Al final, axios convierte estos parámetros en una cadena de consulta en la URL, resultando en una URL como esta: 'https://v1.american-football.api-sports.io/games?league=1&season=2024&timezone=America/Chicago'
              params: {
                league: 1,
                season: 2024,
                timezone: "America/Chicago"
              },
              // Headers es un objeto que contine encabezados HTTP adicionales que se envían junto con la solicitud, en este caso se utiliza un encabezado de autorización para acceder a la API.
              headers: {
                'x-apisports-key': 'bddd9dbbee5e3fd68dd56effd15503e6'
              }
          });     
          
          // Resumen del Código

          // axios.get: Realiza una solicitud GET.
          // Endpoint: 'https://v1.american-football.api-sports.io/games'.
          // params: Especifica los parámetros de liga, temporada y zona horaria.
          // headers: Incluye la clave de la API para la autenticación.
          // Respuesta (response): Contendrá los datos solicitados o información sobre cualquier error.

          this.partidos = response.data.response;         

          // Obtenemos todos los partidos de la temporada de la siguiente manera:
          // 1.- Tenemos en la variable partidos todos los partidos de toda la temporada que obtuvimos mediante la api de API-Sports.
          // 2.- Aplicamos la función .filter para filtrar los partidos a aquellos que cumplan con las condiciones dadas, en este caso, game.game.stage == 'Regular Season' && game.game.week == this.semana_selected
          // 3.- semana_selected es una variable inicializada en el data de la aplicación vue como cadena de texto = 'Week 1', de esta forma se visualizan por default los partidos de la semana 1 de la temporada regular.
          this.partidos_filtro = this.partidos.filter((game) => game.game.stage == 'Regular Season' && game.game.week == this.semana_selected);     
          // Resultado: this.partidos_filtro contiene todos los partidos de temporada regular de la semana 1.  

          // Obtenemos las semanas de la temporada regular de la siguiente manera:
          // 1.- Aplicamos la función .filter la cual itera sobre los elementos del arreglo partidos y crea un nuevo arreglo en el cual solo estarán aquellos que cumplan con el filtro indicado, en este caso game.game.stage == 'Regular Season'
          // 2.- Al nuevo arreglo que contiene únicamente partidos de temporada regular le aplicamos la función map, la cual de igual forma itera sobre el arreglo creado y crea uno nuevo únicamente con el valor indicado, en este caso game.game.week
          // 3.- Creamos un new Set (Set es una estructura de datos que solo permite valores únicos) lo cual elimina automáticamente los valores duplicados dejando de esta forma una lista de semanas únicas.
          // 4.- Por último aplicamos el operador de propagación (...) el cual convierte el Set en un arreglo.          
          this.semanas = [...new Set(this.partidos.filter((game) => game.game.stage == 'Regular Season').map((game) => game.game.week))];
          // Resultado: this.semanas contiene todas las semanas úniocas en las que se jugaron partidos de la temporada regular, sin duplicados.

        }
        catch (error){
          console.log(error);
        }
        finally{
          this.loading = false;
        }

      }
    }
  };
  </script>
  
  <style scoped>
  .nfl-results {
    text-align: center;    
  }

  </style>
  