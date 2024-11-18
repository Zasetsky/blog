<script setup lang="ts">
import ArticleThumbnail from "@/components/ArticleThumbnail.vue";
import Pagination from "@/components/Pagination.vue";
import { useArticles } from "@/hooks/useArticles";

const { articles, uniqueTags, pagination, updatePage, likeArticle } =
  useArticles();
</script>

<template>
  <div class="articles-page">
    <div class="articles-page__content">
      <!-- Секция тегов -->
      <aside class="articles-page__tags">
        <div class="articles-page__tags-list">
          <el-tag
            v-for="tag in uniqueTags"
            :key="tag.id"
            type="info"
            class="articles-page__tag"
          >
            {{ tag.name }}
          </el-tag>
        </div>
      </aside>

      <!-- Секция статей -->
      <main class="articles-page__articles">
        <ArticleThumbnail
          v-for="article in articles"
          :key="article.id"
          :article="article"
          :like="likeArticle"
        />

        <div class="articles-page__pagination">
          <Pagination
            v-if="articles.length"
            :pagination="pagination"
            @updatePage="updatePage"
          />
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped lang="scss">
.articles-page {
  &__content {
    display: flex;
    gap: 20px;
  }

  &__tags {
    flex: 1;
    max-width: 300px;
  }

  &__tags-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  &__tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  &__tag {
    cursor: pointer;

    &:hover {
      background-color: #3498db;
      color: #fff;
    }
  }

  &__articles {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  &__pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
  }

  &__title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
  }
}
</style>
