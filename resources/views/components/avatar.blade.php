@php
    use Illuminate\Support\Str;

    $avatar = $src ?? null;

    $url = $avatar
        ? (Str::startsWith($avatar, 'http')
            ? $avatar
            : asset('storage/' . $avatar))
        : asset('build/img/userpic.jpg');
@endphp

<img src="{{ $url }}" {{ $attributes }}>
