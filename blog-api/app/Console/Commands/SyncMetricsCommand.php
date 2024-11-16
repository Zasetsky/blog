<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleService;

class SyncMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизирует лайки и просмотры из Redis в базу данных';

    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Начало синхронизации метрик...');
        $this->articleService->syncToDatabase();
        $this->info('Синхронизация завершена.');
    }
}
