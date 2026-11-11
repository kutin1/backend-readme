<?php

namespace App\Services\Thumbnail;

interface ThumbnailServiceInterface
{
    public function makePreview(string $url): string;
}
