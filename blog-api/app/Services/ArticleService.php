<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\Paginator;

class ArticleService
{
	/**
	 * Получить список статей с комментариями и тегами.
	 *
	 * @return Paginator
	 */
	public function getArticlesWithRelations(): Paginator
	{
		$articles = Article::with(['tags'])->simplePaginate(10);

		foreach ($articles as $article) {
			$article->likes_count += $this->getLikesFromRedis($article->id);
			$article->views_count += $this->getViewsFromRedis($article->id);
		}

		return $articles;
	}

	/**
	 * Получить одну статью и тегами по ID.
	 *
	 * @param string $id
	 * @return Article
	 */
	public function getArticleByIdWithRelations(string $id): Article
	{
		$article = Article::with(['tags'])->findOrFail($id);

		$article->likes_count += $this->getLikesFromRedis($id);
		$article->views_count += $this->getViewsFromRedis($id);

		return $article;
	}

	/**
	 * Инкрементирует счётчик лайков в Redis.
	 *
	 * @param string $articleId
	 * @return void
	 */
	public function incrementLikes(string $articleId): int
	{
		$key = "article:{$articleId}:likes";
		$likes = Redis::incr($key);

		return $likes;
	}

	/**
	 * Инкрементирует счётчик просмотров в Redis.
	 *
	 * @param string $articleId
	 * @return void
	 */
	public function incrementViews(string $articleId): int
	{
		$key = "article:{$articleId}:views";
		$views = Redis::incr($key);

		return $views;
	}

	/**
	 * Получить количество лайков из Redis.
	 *
	 * @param string $articleId
	 * @return int
	 */
	private function getLikesFromRedis(string $articleId): int
	{
		$key = "article:{$articleId}:likes";
		return (int) Redis::get($key) ?? 0;
	}

	/**
	 * Получить количество просмотров из Redis.
	 *
	 * @param string $articleId
	 * @return int
	 */
	private function getViewsFromRedis(string $articleId): int
	{
		$key = "article:{$articleId}:views";
		return (int) Redis::get($key) ?? 0;
	}

	/**
	 * Синхронизирует лайки и просмотры из Redis в базу данных.
	 *
	 * @return void
	 */
	public function syncToDatabase(): void
	{
		$viewKeys = Redis::keys("article:*:views");
		$likeKeys = Redis::keys("article:*:likes");

		Redis::pipeline(function ($pipe) use ($viewKeys, $likeKeys) {
			foreach ($viewKeys as $key) {
				$this->syncSingleMetric($pipe, $key, 'views_count');
			}

			foreach ($likeKeys as $key) {
				$this->syncSingleMetric($pipe, $key, 'likes_count');
			}
		});
	}

	/**
	 * Синхронизирует отдельную метрику из Redis в базу данных.
	 *
	 * @param mixed $pipe
	 * @param string $key
	 * @param string $field
	 * @return void
	 */
	private function syncSingleMetric($pipe, string $key, string $field): void
	{
		try {
			$articleId = str_replace(['article:', ':views', ':likes'], '', $key);
			$value = Redis::get($key);

			if ($value > 0) {
				Article::where('id', $articleId)->increment($field, $value);
				$pipe->del($key); // Удаляем ключ после синхронизации
			}
		} catch (\Exception $e) {
			Log::error("Ошибка синхронизации метрики {$field} для ключа {$key}: {$e->getMessage()}");
		}
	}
}


