import { Article } from "@/stores/articles/types/IArticles";
import httpClient from "../http";
import { ArticlesResponse } from "@/stores/articles/types/IArticlesStore";

export class ArticlesApi {
  // Получение всех статей
  static async fetchArticles(page = 1): Promise<ArticlesResponse> {
    const response = await httpClient.get<ArticlesResponse>(
      `/articles?page=${page}`
    );
    return response.data;
  }

  // Получение конкретной статьи
  static async fetchArticle(articleId: string): Promise<Article> {
    const response = await httpClient.get<Article>(`/articles/${articleId}`);
    return response.data;
  }

  // Увеличение количества лайков
  static async likeArticle(
    articleId: string
  ): Promise<{ likes_count: number }> {
    const response = await httpClient.post<{ likes_count: number }>(
      `/articles/${articleId}/like`
    );
    return response.data;
  }

  // Увеличение просмотров
  static async viewArticle(
    articleId: string
  ): Promise<{ views_count: number }> {
    const response = await httpClient.post<{ views_count: number }>(
      `/articles/${articleId}/view`
    );
    return response.data;
  }
}

export const articlesApi = ArticlesApi;
