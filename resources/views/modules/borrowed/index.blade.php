@extends('layouts.app')
@section('page-name', 'Borrowed Books')
@section('page-content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ asset('assets/images/parallax/bgparallax-07.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1>Borrowed Books</h1>
                        <ol class="tg-breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="tg-active">Borrowed Books</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="tg-main" class="tg-main tg-haslayout">
        <div class="tg-sectionspace tg-haslayout">
            <div class="container">
                <div class="row">
                    @foreach ($books as $bookItem)
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
                            <div class="tg-postbook">
                                <figure class="tg-featureimg">
                                    <div class="tg-bookimg">
                                        <div class="tg-frontcover">
                                            <img src="{{ $bookItem->book?->cover_picture }}"
                                                alt="{{ $bookItem->book?->title }}">
                                        </div>
                                        <div class="tg-backcover">
                                            <img src="{{ $bookItem->book?->cover_picture }}"
                                                alt="{{ $bookItem->book?->title }}">
                                        </div>
                                    </div>
                                    @if ($bookItem->book?->is_ebook === 'YES')
                                        <a class="tg-btnaddtowishlist"
                                            href="{{ route('book', ['slug' => $bookItem->book?->slug]) }}">
                                            <span>Read</span>
                                        </a>
                                    @endif
                                </figure>
                                <div class="tg-postbookcontent">
                                    <ul class="tg-bookscategories">
                                        @if ($bookItem->book?->genres->count() > 0)
                                            @foreach ($bookItem->book?->genres as $genreItem)
                                                <li><a
                                                        href="{{ route('books') . '?genre=' . $genreItem->genre_id }}">{{ $genreItem->genre?->name }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="tg-themetagbox">
                                        <span class="tg-themetag">
                                            <a style="color: #ffffff"
                                                href="{{ route('books') . '?category=' . $bookItem->book?->category_id }}">{{ ucfirst($bookItem->book?->category?->name) }}</a>
                                        </span>
                                    </div>
                                    <div class="tg-booktitle">
                                        <h3>
                                            <a
                                                href="{{ $bookItem->book?->is_ebook == 'YES' ? route('book', ['slug' => $bookItem->book?->slug]) : '#' }}">{{ $bookItem->book?->title }}</a>
                                        </h3>
                                    </div>
                                    <span class="tg-bookwriter">By: <a
                                            href="{{ route('books') . '?author=' . $bookItem->book?->author }}">{{ $bookItem->book?->author }}</a></span>
                                    <span class="tg-bookwriter">Date Borrowed:
                                        {{ date('j M, Y h:i:a', strtotime($bookItem->borrow_date)) }}</span>
                                    <span class="tg-bookwriter">Return Date:
                                        {{ date('j M, Y h:i:a', strtotime($bookItem->return_date)) }}</span>
                                    @if ($bookItem->returned_date)
                                        <span class="tg-bookwriter">Returned Date:
                                            {{ date('j M, Y h:i:a', strtotime($bookItem->returned_date)) }}</span>
                                    
                                        <a class="tg-btn tg-btnaddtowishlist"
                                            href="javascript:void(0);">
                                            <em>Returned</em>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $books->links() }}
            </div>
        </div>
    </main>
@endsection
