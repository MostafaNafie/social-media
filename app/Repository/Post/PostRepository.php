<?php

namespace App\Repository\Post;

use App\Models\Post;
use App\Repository\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository extends BaseRepository implements IPostRepository
{
    protected static $model;

    public function __construct(Post $post)
    {
        self::$model = $post;
    }

    public function paginate(?int $perPage = null, array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator
    {
        return self::$model->with([
            'creator',
            'creator.profile',
            'comments',
            'comments.commentor',
            'comments.commentor.profile'
        ])
//            ->withCount(['comments'])
            ->paginate(
                $perPage,
                $columns,
                $pageName,
                $page
            );
    }
}
