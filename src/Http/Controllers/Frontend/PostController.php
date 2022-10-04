<?php

declare(strict_types=1);

namespace Companypost\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Companybase\Http\Controllers\Frontend\BaseController;
use Illuminate\Http\Request;
use Companypost\Models\Post;

class PostController extends BaseController
{
    public function __construct(Post $post)
    {
		parent::__construct();

        $this->post = $post;
    }

    public function index()
    {
        $posts = $this->getPosts(5);

        $tags = $this->getTags();

        $featurePosts = $this->getFeaturePosts();

        return view('companybase::frontend.post.index', compact('posts', 'tags', 'featurePosts'));
    }

    public function detail($id)
    {
        $post = $this->post->findOrFail($id);

        return view('companybase::frontend.post.detail', compact('post'));
    }

    public function postOftags($id)
    {
        $postOftags = $this->post->where('tag_id', $id)->get();

        $featurePosts = $this->getFeaturePosts();

        $tags = $this->getTags();

        return view('companybase::frontend.post.tags.index', compact('postOftags', 'featurePosts', 'tags'));
    }

}
