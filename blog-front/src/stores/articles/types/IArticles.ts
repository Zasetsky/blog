export interface Tag {
  id: string;
  name: string;
}

export interface Article {
  id: string;
  title: string;
  content: string;
  likes_count: number;
  views_count: number;
  tags: Tag[];
}
