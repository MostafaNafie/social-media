<?php

namespace App\Http\Controllers\API\V1\Post;

use App\Http\Controllers\API\V1\APIV1Controller;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedResource;
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
        return $this->okWithPagination(
            FeedResource::collection($this->feedService->getMyFeed())
        );
    }
}
