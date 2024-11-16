import { Comment } from "@/stores/comments/types/IComments";
import httpClient from "../http";

export class CommentsApi {
  // Получение комментариев для статьи
  static async fetchComments(articleId: string): Promise<Comment[]> {
    const response = await httpClient.get<Comment[]>(
      `/articles/${articleId}/comments`
    );
    return response.data;
  }

  // Добавление комментария
  static async addComment(articleId: string, text: string): Promise<Comment> {
    const response = await httpClient.post<Comment>(
      `/articles/${articleId}/comment`,
      { text }
    );
    return response.data;
  }
}

export const commentsApi = CommentsApi;
