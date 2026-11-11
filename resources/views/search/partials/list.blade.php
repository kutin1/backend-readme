@foreach($posts as $post)
    @include('search.partials.types.' . $post->contentType->icon, ['post' => $post])
@endforeach
