<?php

declare(strict_types=1);

namespace Companypost\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Companybase\Models\Admin;
use Companybase\Models\Tag;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array
     */
    protected $fillable = ['title', 'slug', 'quote', 'content', 'photo', 'photo_name', 'is_featured', 'user_id', 'tag_id', 'status'];

    // protected $guarded = [];

    protected $dates 	= ['deleted_at'];

    /**
     *  Get the name record associated with the user.
     *
     * @param null
     * @return belongsTo
     */
    public function user() : belongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id')->withDefault();
    }

    /**
     *  Get the name record associated with the tag.
     *
     * @param null
     * @return belongsTo
     */
    public function tag() : belongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id')->select(['id', 'name'])->withDefault();
    }

    /**
     * Scope a query to only include tags of a given type.
     *
     * @param  $query
     * @return Builder
     */
    public function scopeActive($query) : builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Count model
     *
     * @param null
     * @return int
     */
    public static function countPost() : int
    {
        $counts = Post::active()->count();

        if($counts){
            return $counts;
        }

        return 0;
    }
}
