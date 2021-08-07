<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\API\V1\APIV1Controller;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
use App\Models\Comment;
use App\Services\Post\FeedService;
use Illuminate\Http\Request;

class FeedController extends APIV1Controller
{
    private $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    public function feed()
    {
//        Comment::query()->create([
//            'body' => 'Hii comment',
//            'post_id' => 100,
//            'commented_by' => 1
//        ]);
//
//        Comment::query()->where('id', 18)->withTrashed()->forceDelete();

//        return $this->feedService->getMyFeed();
        return $this->okWithPagination(
            FeedResource::collection($this->feedService->getMyFeed())
        );
    }
}
