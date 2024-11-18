import { Comment } from "@/stores/comments/types/IComments";
import httpClient from "../http";

export class CommentsApi {
  // Получение комментариев для статьи
  static async fetchComments(articleId: string): Promise<Comment[]> {
    const response = await httpClient.get<Comment[]>(
      `/articles/${articleId}/get-comments`
    );
    return response.data;
  }

  // Добавление комментария
  static async addComment(
    articleId: string,
    subject: string,
    body: string
  ): Promise<Comment> {
    const response = await httpClient.post<Comment>(
      `/articles/${articleId}/comment`,
      { subject, body }
    );
    return response.data;
  }
}

export const commentsApi = CommentsApi;
