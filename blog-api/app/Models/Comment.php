<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
	protected $fillable = ['article_id', 'subject', 'body'];

	public $incrementing = false;

	protected $keyType = 'string';

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			if (empty($model->{$model->getKeyName()})) {
				$model->{$model->getKeyName()} = (string) Str::uuid();
			}
		});
	}

	public function article()
	{
		return $this->belongsTo(Article::class);
	}
}
