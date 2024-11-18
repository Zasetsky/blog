<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public string $articleId;
    public string $subject;
    public string $body;

    /**
     * Создание экземпляра задачи.
     *
     * @param string $articleId
     * @param string $subject
     * @param string $body
     */
    public function __construct(string $articleId, string $subject, string $body)
    {
        $this->articleId = $articleId;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function handle(): void
    {
        try {
            // Имитация долгой операции
            sleep(600);

            // Создаём комментарий
            Comment::create([
                'article_id' => $this->articleId,
                'subject' => $this->subject,
                'body' => $this->body,
            ]);

            Log::info("Комментарий для статьи с ID {$this->articleId} успешно создан.");
        } catch (\Exception $e) {
            Log::error("Ошибка при создании комментария для статьи с ID {$this->articleId}: {$e->getMessage()}");
        }
    }
}
