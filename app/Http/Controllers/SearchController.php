<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService
    ) {
    }

    public function __invoke(SearchRequest $request)
    {
        $rawQuery = $request->q;

        // Убираем решётку для хайлайта
        $cleanQuery = ltrim($rawQuery, '#');

        $results = $this->searchService->search($rawQuery);

        return view('search.results', [
            'query' => $cleanQuery,
            'rawQuery' => $rawQuery,
            'users' => $results['users'],
            'posts' => $results['posts'],
            'tagPosts' => $results['tagPosts'],
            'commentPosts' => $results['commentPosts'],
        ]);
    }
}
