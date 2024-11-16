import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import ElementPlus from "element-plus";
import "element-plus/dist/index.css";
import "@/assets/styles/main.scss";

const pinia = createPinia();

createApp(App).use(router).use(pinia).use(ElementPlus).mount("#app");
