<script setup lang="ts">
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

const navigateWithQuery = (routeName: string) => {
  const query = route.query.page ? { page: route.query.page } : {};
  router.push({ name: routeName, query });
};
</script>

<template>
  <header class="header">
    <h3 class="header__blog-name">Блог</h3>

    <el-menu
      :router="false"
      mode="horizontal"
      :default-active="route.name"
      text-color="#a0a0a0"
      active-text-color="#3498db"
      background-color="transparent"
      class="header__menu"
    >
      <el-menu-item index="Home" @click="navigateWithQuery('Home')">
        На главную
      </el-menu-item>
      <el-menu-item index="Articles" @click="navigateWithQuery('Articles')">
        Каталог статей
      </el-menu-item>
    </el-menu>
  </header>
</template>

<style scoped lang="scss">
.header {
  display: flex;
  align-items: center;
  margin-bottom: 10px;

  &__menu {
    flex-grow: 1;
    justify-content: flex-end;
    width: max-content;
    border-bottom: none !important;
  }
}

.el-menu-item {
  padding: 5px 15px;
  color: #a0a0a0;

  &:focus {
    background-color: inherit !important;
  }
}

.el-menu-item.is-active {
  font-weight: bold;
}
</style>
