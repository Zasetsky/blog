<?php
declare(strict_types=1);

namespace App\Services;

use App\DTOs\CommentDTO;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Exception;

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
		dispatch(function () use ($dto) {
			try {
				// Создаём комментарий
				Comment::create([
					'article_id' => $dto->articleId,
					'subject' => $dto->subject,
					'body' => $dto->body,
				]);

				// Имитация долгой операции
				sleep(600);
			} catch (Exception $e) {
				// Логируем ошибку
				Log::error("Ошибка при создании комментария для статьи с ID {$dto->articleId}: {$e->getMessage()}");
			}
		});
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
