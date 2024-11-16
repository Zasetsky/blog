<script setup lang="ts">
import { useRoute } from "vue-router";

const route = useRoute();
</script>

<template>
  <el-menu
    :router="true"
    mode="horizontal"
    :default-active="route.name"
    text-color="#a0a0a0"
    active-text-color="#3498db"
    background-color="transparent"
    class="menu-right"
  >
    <el-menu-item index="Home" :route="{ name: 'Home' }"
      >На главную</el-menu-item
    >
    <el-menu-item index="Articles" :route="{ name: 'Articles' }">
      Каталог статей
    </el-menu-item>
  </el-menu>
</template>

<style scoped>
.menu-right {
  justify-content: flex-end;
  border-bottom: none;
  font-size: 16px;
  box-shadow: none;
}

.el-menu-item {
  padding: 5px 15px; /* Уменьшена высота */
  height: 40px;
  line-height: 40px;
  transition: all 0.3s ease;
  color: #a0a0a0;
}

.el-menu-item.is-active {
  font-weight: bold;
}
</style>
