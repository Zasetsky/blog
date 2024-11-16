import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Home",
    component: () => import("@/views/Home.vue"),
  },
  {
    path: "/articles",
    name: "Articles",
    component: () => import("@/views/Articles.vue"),
  },
  {
    path: "/articles/:slug",
    name: "ArticleDetails",
    component: () => import("@/views/ArticleDetails.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
