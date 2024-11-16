<script setup lang="ts">
import { onMounted } from "vue";
import Navbar from "@/components/Navbar.vue";
import ArticleThumbnail from "@/components/ArticleThumbnail.vue";
import { useArticlesStore } from "@/stores/articles/articlesStore";
import { storeToRefs } from "pinia";
import { ElLoading } from "element-plus";

const articlesStore = useArticlesStore();
const { articles } = storeToRefs(articlesStore);

onMounted(async () => {
  if (!articles.value.length) {
    const loading = ElLoading.service({
      lock: true,
      text: "Загрузка...",
      background: "rgba(0, 0, 0, 0.7)",
    });
    await articlesStore.fetchArticles();
    loading.close();
  }
});
</script>

<template>
  <div>
    <Navbar />
    <h1 class="main-page__title">Главная страница</h1>
    <div class="main-page__grid">
      <div
        v-for="article in articles"
        :key="article.id"
        class="main-page__grid-item"
      >
        <ArticleThumbnail
          :article="article"
          :like="articlesStore.likeArticle"
        />
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.main-page {
  &__title {
    margin-bottom: 20px;
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;

    @media (max-width: 768px) {
      grid-template-columns: repeat(2, 1fr);
    }

    @media (max-width: 480px) {
      grid-template-columns: 1fr;
    }
  }
}
</style>
