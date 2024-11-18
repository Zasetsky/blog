import { onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useArticlesStore } from "@/stores/articles/articlesStore";
import { storeToRefs } from "pinia";
import { ElLoading } from "element-plus";

export function useArticles() {
  const articlesStore = useArticlesStore();
  const { articles, pagination, uniqueTags } = storeToRefs(articlesStore);
  const { likeArticle } = articlesStore;

  const route = useRoute();
  const router = useRouter();

  const loadArticles = async (page?: number) => {
    const currentPage = page || Number(route.query.page) || 1;

    // Проверяем, нужно ли делать запрос
    if (
      pagination.value.current_page === currentPage &&
      articles.value.length > 0
    ) {
      return;
    }

    pagination.value.current_page = currentPage;

    const loading = ElLoading.service({
      lock: true,
      text: "Загрузка...",
      background: "rgba(0, 0, 0, 0.7)",
    });

    await articlesStore.fetchArticles();

    // Скролл наверх после загрузки
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });

    loading.close();
  };

  // Слежение за изменением query и подгрузка статей
  watch(
    () => route.query.page,
    async (newPage) => {
      if (newPage) {
        await loadArticles(Number(newPage));
      }
    }
  );

  // Обновление query параметра при смене страницы
  const updatePage = (page: number) => {
    router.push({ query: { ...route.query, page: page.toString() } });
  };

  onMounted(async () => {
    await loadArticles();
  });

  return {
    articles,
    pagination,
    uniqueTags,
    likeArticle,
    updatePage,
  };
}
