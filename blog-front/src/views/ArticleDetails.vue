<script setup lang="ts">
import CommentForm from "@/components/CommentForm.vue";
import CommentsList from "@/components/CommentsList.vue";
import { useArticlesStore } from "@/stores/articles/articlesStore";
import { useCommentsStore } from "@/stores/comments/commentsStore";
import { ElLoading } from "element-plus";
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const articleId = route.params.slug as string;

const articlesStore = useArticlesStore();
const { articleDetails } = storeToRefs(articlesStore);
const { fetchArticleDetails, viewArticle, likeArticle } = articlesStore;

const commentsStore = useCommentsStore();
const { comments } = storeToRefs(commentsStore);

const { fetchComments, addComment } = commentsStore;

const isLoading = ref(false);

// Очищаем данные, если это другая статья
if (articleDetails.value?.id !== articleId) {
  articleDetails.value = null;
}

const handleLike = async () => {
  if (isLoading.value || !articleDetails.value) return;

  const loading = ElLoading.service({
    lock: true,
    text: "Обновление лайков...",
    background: "rgba(0, 0, 0, 0.7)",
  });

  try {
    await likeArticle(articleDetails.value.id);
  } finally {
    loading.close();
  }
};

const fetchArticleData = async () => {
  const loading = ElLoading.service({
    lock: true,
    text: "Загрузка статьи...",
    background: "rgba(0, 0, 0, 0.7)",
  });

  try {
    await fetchArticleDetails(articleId);

    await fetchComments(articleId);

    if (articleDetails.value && articleDetails.value.id === articleId) {
      await viewArticle(articleId);
    }
  } finally {
    loading.close();
  }
};

onMounted(async () => {
  await fetchArticleData();
});
</script>

<template>
  <div v-if="articleDetails" class="article-detail">
    <!-- Заголовок статьи -->
    <h1 class="article-detail__title">{{ articleDetails.title }}</h1>

    <!-- Информация о статье -->
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

    <!-- Теги -->
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

    <!-- Контент статьи -->
    <p class="article-detail__content">{{ articleDetails.content }}</p>

    <!-- Разделитель -->
    <div class="article-detail__divider"></div>

    <!-- Комментарии -->
    <div class="article-detail__comments">
      <CommentsList :comments="comments" />
      <h2>Оставить комментарий</h2>
      <CommentForm :articleId="articleId" :addComment="addComment" />
    </div>
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

  &__divider {
    margin: 20px 0;
    border-top: 1px solid #e0e0e0;
  }

  &__comments {
    h2 {
      margin: 0;
      margin-bottom: 20px;
    }
  }
}
</style>
