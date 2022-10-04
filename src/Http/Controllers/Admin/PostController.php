<?php

declare(strict_types=1);

namespace Companypost\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Companypost\Repositories\Contracts\PostRepositoryInterface;
use Companybase\Repositories\Contracts\TagRepositoryInterface;
use Companypost\Http\Requests\PostRequest;
use Illuminate\Support\Str;
use Auth;
use DB;
use Log;
use Exception;
use Storage;
use Companypost\Traits\StorageImageTrait;
use File;
use Companypost\Models\Post;

class PostController extends Controller
{
    use StorageImageTrait;

    protected $post;

    protected $tag;

    public function __construct(PostRepositoryInterface $post, TagRepositoryInterface $tag)
    {
        $this->post = $post;

        $this->tag = $tag;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->post->all(['id', 'title', 'quote', 'photo', 'user_id', 'tag_id', 'status'], ['tag']);

        return view('companybase::admin.post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = $this->tag->all(['id', 'name']);

        return view('companybase::admin.post.add', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            $dataUploadFeatureImage = $this->storageTraitUpload($request, 'photo', 'posts');

            if (!empty($dataUploadFeatureImage)) {
                $photo_name = $dataUploadFeatureImage['file_name'];

                $photo      = $dataUploadFeatureImage['file_path'];
            }

            $this->post->store(
                array_merge(
                    $request->validated(),
                    [
                        'slug' => Str::slug($request->title),
                        "photo_name" => $photo_name,
                        "photo" => $photo,
                        'user_id' => Auth::user()->id
                    ]
                )
            );

            DB::commit();

            return redirect()->back()->withInput($request->input())->with('message','Thêm thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error(loi): ' . $exception->getMessage() . ' --- Line : ' . $exception->getLine());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $tags = $this->tag->all(['id', 'name']);

        $post = $this->post->findByid($id);

        return view('companybase::admin.post.edit', compact('post', 'tags'));
    }

    public function removeImage($id){
        $imagePath = public_path($this->post->findById($id)->photo);

        $this->unlinkImage($imagePath);

        return;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, int $id)
    {
        try {
            $post = $this->post->findById($id);

            $dataUploadFeatureImage = $this->storageTraitUpload($request, 'photo', 'posts');

            $postData = $request->validated();

            if (!empty($dataUploadFeatureImage)) {
                //remove image
                $this->removeImage($id);

                $photo_name = $dataUploadFeatureImage['file_name'];

                $photo      = $dataUploadFeatureImage['file_path'];

                $postData = array_merge(
                    $request->validated(),
                    [
                        'slug' => Str::slug($request->title),
                        "photo_name" => $photo_name,
                        "photo" => $photo,
                        'user_id' => Auth::user()->id
                    ]
                );
            }

            $post->update($postData);

            return redirect()->route('post.index')->with('message','Sửa thành công');
        } catch (\Exception $exception) {
            Log::error('Error(loi): ' . $exception->getMessage() . ' --- Line : ' . $exception->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $this->post->delete($id);

            return redirect()->route('post.index')->with('message','Xóa thành công');
        } catch (\Exception $exception) {
            Log::error('Error(loi): ' . $exception->getMessage() . ' --- Line : ' . $exception->getLine());
        }
    }
}
