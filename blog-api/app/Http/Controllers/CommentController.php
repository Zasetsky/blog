<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CommentDTO;
use App\Services\CommentService;
use App\Http\Requests\CreateCommentRequest;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function __construct(
		private CommentService $commentService
	) {
	}

	/**
	 * Создать комментарий для статьи.
	 * @param CreateCommentRequest $request
	 * @return JsonResponse
	 */
	public function createComment(CreateCommentRequest $request): JsonResponse
	{
		$dto = CommentDTO::fromRequest($request);

		$this->commentService->processCommentCreation($dto);

		return response()->json(['message' => 'Комментарий будет обработан'], 200);
	}

	/**
	 * Получить комментарии для статьи.
	 *
	 * @param string $articleId
	 * @return JsonResponse
	 */
	public function getComments(string $articleId): JsonResponse
	{
		$comments = $this->commentService->getCommentsByArticleId($articleId);
		return response()->json($comments, 200);
	}
}

