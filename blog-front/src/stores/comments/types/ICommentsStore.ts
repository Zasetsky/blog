import { Comment } from "./IComments";

export interface CommentsState {
  comments: Comment[];
  loading: boolean;
  error: string | null;
}
