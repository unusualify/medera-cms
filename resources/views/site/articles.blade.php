@php
    $category_id = $category ? $category->id : null;
    $meta = null;
    if(!$category){
        $setting_block = TwillAppSettings::getGroupDataForSectionAndName('static-pages', 'library');
        $meta = arrayToObject([
            'title' => $setting_block->translatedInput('title'),
            'description' => $setting_block->translatedInput('description'),
        ]);
    }
@endphp
@extends('site.master', ['meta' => $meta])
@section('content')
    <div class="container">
        <div class="library-content">
            <h1>
                {{ __('frontend.Library')   }}
            </h1>
            <p>
                {{ __('frontend.library-desc') }}
            </p>
        </div>
    </div>

    <div class="container xlarge">
        <div class="library-tabbed-list">
            <a href="{{ route('articles') }}" class="primary-cta __tabbed {{ !$category_id ? 'active' : '' }}" data-tab-id="0">
                {{ __('frontend.All') }}
            </a>
            @foreach($categories as $key => $c)
                <a href="{{ route('blog_category', $c->slug) }}" class="primary-cta __tabbed {{ $c->id === $category_id ? 'active' : '' }}" data-tab-id="{{ $key }}">
                    {{ $c->title }}
                </a>
            @endforeach
        </div>
        <div class="library-search-bar">
            <form action="{{ route('articles') }}" method="GET" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $searchQuery ?? '' }}"
                    placeholder="{{ __('Search articles...') }}"
                >
                <button type="submit">{{ __('Search') }}</button>
            </form>
            @if(isset($searchQuery))
            <div class="search-results">
                <h2>{{ __('Search results for:') }} "{{ $searchQuery }}"</h2>
                <p>{{ $list->total() }} {{ __('results found') }}</p>
            </div>
        @endif
        </div>
    </div>

  



    <div class="container xlarge mb-30">


        <div class="card-display library">

            @if ($list->isNotEmpty())
                @foreach ($list as $article)
                    <x-article-card :article="$article" />
                @endforeach
            @endif

        </div>
        <div class="pagination">

            @if ($list->hasPages())
                {{ $list->links() }}
            @endif
        </div>
    </div>
@endsection
