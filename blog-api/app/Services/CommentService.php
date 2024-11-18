<?php
declare(strict_types=1);

namespace App\Services;

use App\DTOs\CommentDTO;
use App\Jobs\ProcessCommentJob;
use App\Models\Comment;

class CommentService
{
	/**
	 * Обрабатывает создание комментария в фоновом режиме.
	 *
	 * @param CommentDTO $dto
	 * @return void
	 */
	public function processCommentCreation(CommentDTO $dto): void
	{
		// Отправляем задачу в очередь
		ProcessCommentJob::dispatch(
			$dto->articleId,
			$dto->subject,
			$dto->body
		);
	}

	/**
	 * Получить список комментариев для статьи.
	 *
	 * @param string $articleId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getCommentsByArticleId(string $articleId)
	{
		return Comment::where('article_id', $articleId)->get();
	}
}
