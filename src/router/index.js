import { createRouter, createWebHistory } from 'vue-router';
import NFLResults from '../components/NFLResults.vue';
import NFLGame from '../components/NFLGame.vue';

const routes = [
  {
    path: '/',
    name: 'Results',
    component: NFLResults
  },
  {
    path: '/game/:gameId',
    name: 'Game',
    component: NFLGame
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
