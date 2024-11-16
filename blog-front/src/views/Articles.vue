<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import ArticleThumbnail from "@/components/ArticleThumbnail.vue";
import Pagination from "@/components/Pagination.vue";
import { reactive } from "vue";

// Заглушка API
const paginatedArticles = reactive({
  data: [],
  current_page: 1,
  last_page: 1,
});

const loadArticles = (page = 1) => {
  // Запросите статьи с API, включая пагинацию
};
</script>

<template>
  <div>
    <Navbar />
    <h1>Каталог статей</h1>
    <el-row>
      <el-col
        :span="8"
        v-for="article in paginatedArticles.data"
        :key="article.id"
      >
        <ArticleThumbnail :article="article" />
      </el-col>
    </el-row>
    <Pagination :pagination="paginatedArticles" @change-page="loadArticles" />
  </div>
</template>
