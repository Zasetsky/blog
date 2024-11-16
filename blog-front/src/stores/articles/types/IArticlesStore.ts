import { Article } from "./IArticles";

export interface ArticlesState {
  articles: Article[];
  pagination: Pagination | null;
  articleDetails: Article | null;
  loading: boolean;
  error: string | null;
}

export interface Pagination {
  current_page: number;
  per_page: number;
  total: number;
}

export interface ArticlesResponse {
  current_page: number;
  data: Article[];
  first_page_url: string;
  from: number;
  per_page: number;
  to: number;
}
