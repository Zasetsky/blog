<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
	public function __construct(
		private ArticleService $articleService
	) {
	}

	public function incrementLikes(string $articleId): JsonResponse
	{
		$newLikesCount = $this->articleService->incrementLikes($articleId);
		return response()->json(['likes_count' => $newLikesCount], 200);
	}

	public function incrementViews(string $articleId): JsonResponse
	{
		$newViewsCount = $this->articleService->incrementViews($articleId);
		return response()->json(['views_count' => $newViewsCount], 200);
	}

	/**
	 * Получить список статей с комментариями и тегами.
	 *
	 * @return JsonResponse
	 */
	public function index(): JsonResponse
	{
		$articles = $this->articleService->getArticlesWithRelations();
		return response()->json($articles, 200);
	}

	/**
	 * Получить одну статью с комментариями и тегами.
	 *
	 * @param string $id
	 * @return JsonResponse
	 */
	public function show(string $id): JsonResponse
	{
		$article = $this->articleService->getArticleByIdWithRelations($id);
		return response()->json($article, 200);
	}
}
