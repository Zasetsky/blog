<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
	use HasFactory;

	protected $fillable = ['title', 'content', 'likes_count', 'views_count'];

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

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
	}
}
