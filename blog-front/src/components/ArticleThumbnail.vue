<script setup lang="ts">
import { defineProps, ref } from "vue";
import type { Article } from "@/stores/articles/types/IArticles";

const props = defineProps<{
  article: Article;
  like: (id: string) => Promise<void>;
}>();

const isLoading = ref(false);

const handleLike = async () => {
  if (isLoading.value) return;
  isLoading.value = true;
  await props.like(props.article.id);
  isLoading.value = false;
};
</script>

<template>
  <router-link :to="{ name: 'ArticleDetails', params: { slug: article.id } }">
    <el-card
      :body-style="{ padding: '0' }"
      class="article-card"
      v-loading="isLoading"
      element-loading-text="Загрузка..."
    >
      <img
        src="https://via.placeholder.com/400x250?text=Image"
        alt="Article Image"
        class="article-card__image"
      />
      <div class="article-card__content-container">
        <p class="article-card__title">{{ article.title }}</p>
        <p class="article-card__content">
          {{ article.content.slice(0, 100) }}...
        </p>
      </div>

      <div class="article-card__footer">
        <div class="article-card__views">
          <i class="fas fa-eye"></i>
          {{ article.views_count }}
        </div>
        <div class="article-card__likes" @click.stop.prevent="handleLike">
          <i class="fas fa-thumbs-up"></i>
          {{ article.likes_count }}
        </div>
      </div>
    </el-card>
  </router-link>
</template>

<style scoped lang="scss">
.article-card {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 400px;
  justify-content: space-between;
  transition: transform 0.3s, box-shadow 0.3s;

  &__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }

  &__content-container {
    padding: 0 10px;
  }

  &__title {
    font-size: 14px;
    font-weight: bold;
  }

  &__content {
    font-size: 14px;
  }

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  &__footer {
    display: flex;
    justify-content: space-between;
    position: absolute;
    z-index: 10;
    background-color: #fff;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 0 10px 10px 10px;
    font-size: 18px;
  }

  &__views,
  &__likes {
    display: flex;
    align-items: center;
    color: #666;
  }

  &__views i,
  &__likes i {
    margin-right: 5px; /* Отступ между иконкой и числом */
  }

  &__likes:hover {
    color: #ff5a5a;
    transform: scale(1.1);
  }

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
}
</style>
