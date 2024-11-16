<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Tag extends Model
{
	use HasFactory;

	protected $fillable = ['name'];

	public $incrementing = false;

	protected $keyType = 'string';

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			if (empty($model->{$model->getKeyName()})) {
				$model->{$model->getKeyName()} = Str::uuid()->toString();
			}
		});
	}

	public function articles()
	{
		return $this->belongsToMany(Article::class, 'article_tag', 'tag_id', 'article_id');
	}
}
