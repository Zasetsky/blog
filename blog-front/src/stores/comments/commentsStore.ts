import { defineStore } from "pinia";
import { commentsApi } from "@/services/modules/commentsApi";
import { CommentsState } from "./types/ICommentsStore";
import { Comment } from "./types/IComments";

export const useCommentsStore = defineStore("commentsStore", {
  state: (): CommentsState => ({
    comments: [],
    loading: false,
    error: null,
  }),

  getters: {
    getCommentsByArticle:
      (state) =>
      (articleId: string): Comment[] =>
        state.comments.filter((comment) => comment.article_id === articleId),
  },

  actions: {
    // Получение комментариев для статьи
    async fetchComments(articleId: string) {
      this.loading = true;
      this.error = null;
      try {
        const response = await commentsApi.fetchComments(articleId);
        this.comments = response;
      } catch (err) {
        this.error = "Ошибка загрузки комментариев.";
      } finally {
        this.loading = false;
      }
    },

    // Добавление комментария
    async addComment(articleId: string, subject: string, body: string) {
      this.error = null;
      try {
        await commentsApi.addComment(articleId, subject, body);
      } catch (err) {
        this.error = "Ошибка при добавлении комментария.";
      }
    },
  },
});
