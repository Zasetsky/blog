<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;
use App\Models\Article;
use Illuminate\Support\Facades\Log;

class ArticleService
{
	/**
	 * Получить список статей с комментариями и тегами.
	 *
	 * @return LengthAwarePaginator
	 */
	public function getArticlesWithTags(): LengthAwarePaginator
	{
		$articles = Article::with(['tags'])
			->orderBy('created_at', 'desc') // Сортировка LIFO
			->paginate(10);

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
	public function getArticleByIdWithTags(string $id): Article
	{
		$article = Article::with(['tags'])->findOrFail($id);

		$article->likes_count += $this->getLikesFromRedis($id);
		$article->views_count += $this->getViewsFromRedis($id);

		return $article;
	}

	/**
	 * Инкрементирует счётчик лайков в Redis и возвращает общую сумму с БД.
	 *
	 * @param string $articleId
	 * @return int
	 */
	public function incrementLikes(string $articleId): int
	{
		// Проверяем, существует ли статья
		$article = Article::find($articleId);

		if (!$article) {
			Log::warning("Попытка увеличить лайки для несуществующей статьи: {$articleId}");
			return 0;
		}

		// Если статья существует, инкрементируем лайки в Redis
		$key = "article:{$articleId}:likes";
		$likesRedis = Redis::incr($key);

		$likesDb = $article->likes_count;

		return $likesDb + $likesRedis;
	}

	/**
	 * Инкрементирует счётчик просмотров в Redis и возвращает общую сумму с БД.
	 *
	 * @param string $articleId
	 * @return int
	 */
	public function incrementViews(string $articleId): int
	{
		// Проверяем, существует ли статья
		$article = Article::find($articleId);

		if (!$article) {
			Log::warning("Попытка увеличить просмотры для несуществующей статьи: {$articleId}");
			return 0;
		}

		// Если статья существует, инкрементируем просмотры в Redis
		$key = "article:{$articleId}:views";
		$viewsRedis = Redis::incr($key);

		$viewsDb = $article->views_count;

		return $viewsDb + $viewsRedis;
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

		// Получаем значения для всех ключей просмотров
		$viewValues = [];
		if (!empty($viewKeys)) {
			$viewValues = array_combine($viewKeys, Redis::mget($viewKeys));
		}

		// Получаем значения для всех ключей лайков
		$likeValues = [];
		if (!empty($likeKeys)) {
			$likeValues = array_combine($likeKeys, Redis::mget($likeKeys));
		}

		// Удаляем ключи в Redis с помощью pipeline
		Redis::pipeline(function ($pipe) use ($viewKeys, $likeKeys) {
			foreach ($viewKeys as $key) {
				$pipe->del($key);
			}
			foreach ($likeKeys as $key) {
				$pipe->del($key);
			}
		});

		// Синхронизируем метрики просмотров
		foreach ($viewValues as $key => $value) {
			$this->syncSingleMetric($key, 'views_count', (int) $value);
			Log::info("Синхронизирован ключ: {$key} для views_count");
		}

		// Синхронизируем метрики лайков
		foreach ($likeValues as $key => $value) {
			$this->syncSingleMetric($key, 'likes_count', (int) $value);
			Log::info("Синхронизирован ключ: {$key} для likes_count");
		}
	}


	/**
	 * Синхронизирует отдельную метрику из Redis в базу данных.
	 *
	 * @param mixed $pipe
	 * @param string $key
	 * @param string $field
	 * @return void
	 */
	private function syncSingleMetric(string $key, string $field, int $value): void
	{
		try {
			$articleId = str_replace(['article:', ':views', ':likes'], '', $key);

			if ($value > 0) {
				Article::where('id', $articleId)->increment($field, $value);
			}
		} catch (\Exception $e) {
			Log::error("Ошибка синхронизации метрики {$field} для ключа {$key}: {$e->getMessage()}", [
				'key' => $key,
				'field' => $field,
				'value' => $value,
				'trace' => $e->getTraceAsString(),
			]);
		}
	}
}


