<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (DB::table('articles')->count() === 0) {
            $this->call(ArticleSeeder::class);
        }

        if (DB::table('article_tag')->count() === 0) {
            $this->call(ArticleTagSeeder::class);
        }
    }
}
