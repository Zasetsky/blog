<script setup lang="ts">
import { defineProps, ref } from "vue";
import { useRoute } from "vue-router";
import type { Article } from "@/stores/articles/types/IArticles";

const props = defineProps<{
  article: Article;
  like: (id: string) => Promise<void>;
}>();

const isLoading = ref(false);
const route = useRoute();

const isArticlesPage = route.name === "Articles";

const handleLike = async () => {
  if (isLoading.value) return;
  isLoading.value = true;
  await props.like(props.article.id);
  isLoading.value = false;
};

// Генерация ссылки на детальную страницу с учётом query
const getArticleDetailsRoute = (articleId: string) => {
  const query = route.query.page ? { page: route.query.page } : {};
  return { name: "ArticleDetails", params: { slug: articleId }, query };
};
</script>

<template>
  <router-link :to="getArticleDetailsRoute(article.id)">
    <el-card
      :body-style="{ padding: '0' }"
      class="article-card"
      v-loading="isLoading"
      element-loading-text="Загрузка..."
      :style="{ height: isArticlesPage ? 'auto' : '400px' }"
    >
      <img
        src="https://via.placeholder.com/400x250?text=Image"
        alt="Article Image"
        class="article-card__image"
        :style="{ height: isArticlesPage ? '250px' : '200px' }"
      />
      <div class="article-card__content-container">
        <p class="article-card__title">{{ article.title }}</p>
        <p class="article-card__content">
          {{
            isArticlesPage
              ? article.content
              : article.content.slice(0, 100) + "..."
          }}
        </p>
      </div>

      <template #footer>
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
      </template>
    </el-card>
  </router-link>
</template>

<style scoped lang="scss">
.article-card {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.3s, box-shadow 0.3s;

  &__image {
    width: 100%;
    object-fit: cover;
  }

  &__content-container {
    padding: 10px;
    flex-grow: 1;
  }

  &__title {
    font-size: 14px;
    font-weight: bold;
  }

  &__content {
    font-size: 14px;
    margin-bottom: 10px;
  }

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  &__footer {
    display: flex;
    justify-content: space-between;
    position: absolute;
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
    margin-right: 5px;
  }

  &__likes:hover {
    color: #ff5a5a;
    transform: scale(1.1);
  }
}
</style>
