import { Article } from "./IArticles";

export interface ArticlesState {
  articles: Article[];
  pagination: Pagination;
  articleDetails: Article | null;
  loading: boolean;
  error: string | null;
}

export interface Pagination {
  current_page: number;
  per_page: number;
  total_items: number;
}

export interface ArticlesResponse {
  data: Article[];
  pagination: Pagination;
}
