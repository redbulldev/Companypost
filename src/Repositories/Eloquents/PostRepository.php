<?php

namespace Companypost\Repositories\Eloquents;

use Companypost\Repositories\Contracts\PostRepositoryInterface;
use Companybase\Repositories\Eloquents\AbstractRepository;
use Companypost\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
	protected $post;

	function __construct(Post $post)
	{
		$this->post = $post;

		parent::__construct($post);
	}

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return  $this->post->with([
            'tag' => function($query) {
                $query->select('id', 'name');
            }
        ])->get($columns);
    }

}
