@php
    $siteName = config('app.name');
    $pageTitle = trim(preg_replace('/\s+/', ' ', $seoTitle ?? $__env->yieldContent('title')));
    $documentTitle = $pageTitle !== '' ? $siteName.' – '.$pageTitle : $siteName;
    $description = trim(preg_replace('/\s+/', ' ', $seoDescription ?? $__env->yieldContent('meta-description')));
    if ($description === '') {
        $description = $siteName.' is a marketplace where vendors open a store and buyers discover products, place orders, and check out.';
    }
    $description = \Illuminate\Support\Str::limit($description, 160);
    $robots = trim($seoRobots ?? $__env->yieldContent('meta-robots')) ?: 'index, follow';
    $image = trim($seoImage ?? $__env->yieldContent('meta-image'));
    if ($image === '') {
        $image = '/assets/assets/img/hero/vendor-market-stall.jpg';
    }
    if (! str_starts_with($image, 'http')) {
        $image = url($image);
    }
    $params = request()->only(['keyword', 'category', 'vendor', 'sort', 'page']);
    if (request()->routeIs('product-category')) {
        unset($params['category']);
    }
    if (request()->routeIs('vendor-store')) {
        unset($params['vendor']);
    }
    $params = array_filter($params, fn ($value) => $value !== null && $value !== '');
    if ((int) ($params['page'] ?? 1) <= 1) {
        unset($params['page']);
    }
    $canonical = $params === []
        ? request()->url()
        : request()->url().'?'.\Illuminate\Support\Arr::query($params);
    $type = trim($seoType ?? $__env->yieldContent('meta-type')) ?: 'website';
@endphp
<title>{{ $documentTitle }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="en_NG">
<meta property="og:title" content="{{ $documentTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $documentTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
