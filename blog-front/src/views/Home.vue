<script setup lang="ts">
import ArticleThumbnail from "@/components/ArticleThumbnail.vue";
import Pagination from "@/components/Pagination.vue";
import { useArticles } from "@/hooks/useArticles";

const { articles, pagination, updatePage, likeArticle } = useArticles();
</script>

<template>
  <div class="main-page">
    <!-- Сетка статей -->
    <div class="main-page__grid">
      <div
        v-for="article in articles"
        :key="article.id"
        class="main-page__grid-item"
      >
        <ArticleThumbnail :article="article" :like="likeArticle" />
      </div>
    </div>

    <div class="main-page__pagination">
      <Pagination
        v-if="articles.length"
        :pagination="pagination"
        @updatePage="updatePage"
      />
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

    @media (max-width: 768px) {
      grid-template-columns: repeat(2, 1fr);
    }

    @media (max-width: 480px) {
      grid-template-columns: 1fr;
    }
  }

  &__pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
  }
}
</style>
