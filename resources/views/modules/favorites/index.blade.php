@extends('layouts.app')
@section('page-name', 'Favorites')
@section('page-content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ asset('assets/images/parallax/bgparallax-07.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1>Favorites</h1>
                        <ol class="tg-breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="tg-active">Favorites</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="tg-main" class="tg-main tg-haslayout">
        <div class="tg-sectionspace tg-haslayout">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="row">
                    @foreach ($books as $book)
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
                            <div class="tg-postbook">
                                <figure class="tg-featureimg">
                                    <div class="tg-bookimg">
                                        <div class="tg-frontcover">
                                            <img src="{{ $book->cover_picture }}" alt="{{ $book->title }}">
                                        </div>
                                        <div class="tg-backcover">
                                            <img src="{{ $book->cover_picture }}" alt="{{ $book->title }}">
                                        </div>
                                    </div>
                                    <a class="tg-btnaddtowishlist" href="{{ route('book', ['slug' => $book->slug]) }}">
                                        <span>Read</span>
                                    </a>
                                </figure>
                                <div class="tg-postbookcontent">
                                    <ul class="tg-bookscategories">
                                        @if ($book->genres->count() > 0)
                                            @foreach ($book->genres as $genreItem)
                                                <li><a
                                                        href="{{ route('books') . '?genre=' . $genreItem->genre_id }}">{{ $genreItem->genre?->name }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="tg-themetagbox">
                                        <span class="tg-themetag">
                                            <a style="color: #ffffff"
                                                href="{{ route('books') . '?category=' . $book->category_id }}">{{ ucfirst($book->category?->name) }}</a>
                                        </span>
                                    </div>
                                    <div class="tg-booktitle">
                                        <h3>
                                            <a href="{{ route('book', ['slug' => $book->slug]) }}">{{ $book->title }}</a>
                                        </h3>
                                    </div>
                                    <span class="tg-bookwriter">By: <a
                                            href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a></span>
                                    <a class="tg-btn tg-btnstyletwo"
                                        href="{{ route('delete.favorite', ['id' => $book->book_id]) }}">
                                        <em>Remove</em>
                                    </a>
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
