<?php

namespace App\DTOs;

class CommentDTO
{
	public function __construct(
		public string $subject,
		public string $body,
		public string $articleId
	) {
	}

	public static function fromRequest($request): self
	{
		return new self(
			subject: $request->input('subject'),
			body: $request->input('body'),
			articleId: $request->route('article')
		);
	}
}