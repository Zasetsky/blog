import { defineStore } from "pinia";
import { articlesApi } from "@/services/modules/articlesApi";
import { ArticlesState } from "./types/IArticlesStore";

export const useArticlesStore = defineStore("articlesStore", {
  state: (): ArticlesState => ({
    articles: [],
    pagination: null,
    articleDetails: null,
    loading: false,
    error: null,
  }),

  actions: {
    // Получение списка статей
    async fetchArticles(page = 2) {
      this.error = null;
      try {
        const response = await articlesApi.fetchArticles(page);

        this.articles = response.data;
        this.pagination = {
          current_page: response.current_page,
          per_page: response.per_page,
          total: response.to,
        };
      } catch (err) {
        this.error = "Ошибка загрузки статей.";
      }
    },

    // Получение деталей конкретной статьи
    async fetchArticleDetails(articleId: string) {
      this.loading = true;
      try {
        this.articleDetails = await articlesApi.fetchArticle(articleId);
      } catch (err) {
        this.error = "Ошибка загрузки статьи.";
      } finally {
        this.loading = false;
      }
    },

    // Увеличение лайков статьи
    async likeArticle(articleId: string) {
      try {
        const data = await articlesApi.likeArticle(articleId);

        // Обновляем лайки в деталях статьи
        if (this.articleDetails && this.articleDetails.id === articleId) {
          this.articleDetails.likes_count = data.likes_count;
        }

        // Обновляем лайки в массиве статей
        const article = this.articles.find((a) => a.id === articleId);
        if (article) {
          article.likes_count = data.likes_count;
        }
      } catch (err) {
        console.error("Ошибка при добавлении лайка:", err);
      }
    },

    // Увеличение просмотров статьи
    async viewArticle(articleId: string) {
      try {
        const data = await articlesApi.viewArticle(articleId);

        // Обновляем обзоры в деталях статьи
        const article = this.articles.find((a) => a.id === articleId);
        if (this.articleDetails && this.articleDetails.id === articleId) {
          this.articleDetails.views_count = data.views_count;
        }

        // Обновляем обзоры в массиве статей
        if (article) {
          article.views_count = data.views_count;
        }
      } catch (err) {
        console.error("Ошибка при увеличении просмотров:", err);
      }
    },
  },
});
