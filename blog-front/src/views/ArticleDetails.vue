<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import { useArticlesStore } from "@/stores/articles/articlesStore";
import { ElLoading } from "element-plus";
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const articleId = route.params.slug as string;

const articlesStore = useArticlesStore();
const { articleDetails, loading } = storeToRefs(articlesStore);
const isLoading = ref(false);

const handleLike = async () => {
  if (isLoading.value) return;
  isLoading.value = true;
  if (articleDetails.value) {
    await articlesStore.likeArticle(articleDetails.value.id);
  }
  isLoading.value = false;
};

onMounted(async () => {
  if (!articleDetails.value || articleDetails.value.id !== articleId) {
    const loading = ElLoading.service({
      lock: true,
      text: "Загрузка...",
      background: "rgba(0, 0, 0, 0.7)",
    });
    try {
      await articlesStore.fetchArticleDetails(articleId);

      if (
        articlesStore.articleDetails &&
        articlesStore.articleDetails.id === articleId
      ) {
        await articlesStore.viewArticle(articleId);
      }
    } finally {
      loading.close();
    }
  }
});
</script>

<template>
  <Navbar />
  <div v-if="loading"></div>
  <div v-else-if="!articleDetails">
    <el-card :body-style="{ padding: '20px' }">
      <p>Статья не найдена.</p>
    </el-card>
  </div>
  <div v-else>
    <el-card
      :body-style="{ padding: '20px' }"
      v-loading="isLoading"
      element-loading-text="Загрузка..."
    >
      <h1 class="article-detail__title">{{ articleDetails.title }}</h1>
      <div class="article-detail__info">
        <div class="article-detail__views">
          <i class="fas fa-eye"></i>
          <span>{{ articleDetails.views_count }}</span>
        </div>
        <div class="article-detail__likes" @click="handleLike">
          <i class="fas fa-thumbs-up"></i>
          <span>{{ articleDetails.likes_count }}</span>
        </div>
      </div>

      <div class="article-detail__tags">
        <el-tag
          v-for="tag in articleDetails.tags"
          :key="tag.id"
          type="info"
          class="article-detail__tag"
        >
          {{ tag.name }}
        </el-tag>
      </div>

      <p class="article-detail__content">{{ articleDetails.content }}</p>
    </el-card>
  </div>
</template>

<style lang="scss" scoped>
.article-detail {
  &__title {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
  }

  &__info {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
  }

  &__views,
  &__likes {
    display: flex;
    align-items: center;
    font-size: 16px;
    color: #666;

    i {
      margin-right: 5px;
    }
  }

  &__likes {
    cursor: pointer;
    &:hover {
      color: #ff5a5a;
      transform: scale(1.1);
    }
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
  }

  &__tag {
    font-size: 14px;
  }

  &__content {
    font-size: 16px;
    line-height: 1.5;
  }
}
</style>
