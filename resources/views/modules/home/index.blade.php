@extends('layouts.app')
@section('page-name', 'Home')
@section('page-content')
    <div id="tg-homeslider" class="tg-homeslider tg-homeslidervtwo tg-haslayout owl-carousel">
        <div class="item" data-vide-bg="poster: {{ asset('assets/images/slider/img-03.jpg') }}"
            data-vide-options="position: 0% 50%">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-md-push-1 col-lg-10 col-lg-push-1">
                        <div class="tg-slidercontent">
                            <figure class="tg-authorimg"><img src="{{ asset('assets/images/img-03.png') }}"
                                    alt="image description"></figure>
                            <h1>Search your favorite Book</h1>
                            <div class="tg-description">
                                <p> Explore a diverse collection spanning various genres, offering a rich tapestry of
                                    stories and knowledge</p>
                            </div>
                            <div class="tg-btns">
                                <a class="tg-btn tg-active" href="{{ route('books') }}">Find Book</a>
                                <a class="tg-btn" href="{{ route('reading-history') }}">Reading History</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="tg-main" class="tg-main tg-haslayout">
        @if (count($featuredBooks) > 0)
            <section class="tg-sectionspace tg-haslayout">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="tg-sectionhead">
                                <h2><span>Student’s Choice</span>Featured Books</h2>
                                <a class="tg-btn" href="{{ route('books') }}?featured=yes">View All</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="tg-bestsellingbooksslider"
                                class="tg-bestsellingbooksslider tg-bestsellingbooks owl-carousel">
                                @foreach ($featuredBooks as $book)
                                    <div class="item">
                                        <div class="tg-postbook">
                                            <figure class="tg-featureimg">
                                                <div class="tg-bookimg">
                                                    <div class="tg-frontcover"><img src="{{ $book->cover_picture }}"
                                                            alt="{{ $book->title }}"></div>
                                                    <div class="tg-backcover"><img src="{{ $book->cover_picture }}"
                                                            alt="{{ $book->title }}"></div>
                                                </div>
                                                <a class="tg-btnaddtowishlist"
                                                    href="{{ route('book', ['slug' => $book->slug]) }}">
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
                                                    <h3><a
                                                            href="{{ route('book', ['slug' => $book->slug]) }}">{{ $book->title }}</a>
                                                    </h3>
                                                </div>
                                                <span class="tg-bookwriter">By: <a
                                                        href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a></span>
                                                <span class="tg-bookwriter">Book Format:
                                                    {{ ucwords(strtolower(str_replace('_', ' ', $book->ebook_format))) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if (count($newReleaseBooks) > 0)
            <section class="tg-sectionspace tg-haslayout" style="padding: 20px  0 !important">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="tg-sectionhead">
                                <h2><span>Taste The New Spice</span>New Release Books</h2>
                                <a class="tg-btn" href="{{ route('books') }}?new_release=yes">View All</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="tg-relatedproductslider" class="tg-relatedproductslider tg-relatedbooks owl-carousel">
                                @foreach ($newReleaseBooks as $book)
                                    <div class="item">
                                        <div class="tg-postbook">
                                            <figure class="tg-featureimg">
                                                <div class="tg-bookimg">
                                                    <div class="tg-frontcover"><img src="{{ $book->cover_picture }}"
                                                            alt="{{ $book->title }}"></div>
                                                    <div class="tg-backcover"><img src="{{ $book->cover_picture }}"
                                                            alt="{{ $book->title }}"></div>
                                                </div>
                                                <a class="tg-btnaddtowishlist"
                                                    href="{{ route('book', ['slug' => $book->slug]) }}">
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
                                                    <h3><a
                                                            href="{{ route('book', ['slug' => $book->slug]) }}">{{ $book->title }}</a>
                                                    </h3>
                                                </div>
                                                <span class="tg-bookwriter">By: <a
                                                        href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a></span>
                                                <span class="tg-bookwriter">Book Format:
                                                    {{ ucwords(strtolower(str_replace('_', ' ', $book->ebook_format))) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if (count($weeklyBooks) > 0)
            <section class="tg-sectionspace tg-haslayout" style="padding: 20px  0 !important">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="tg-sectionhead">
                                <h2><span>Some Great Books</span>Weekly Picks</h2>
                                <a class="tg-btn" href="{{ route('books') }}?weekly_pick=yes">View All</a>
                            </div>
                        </div>
                        <div id="tg-pickedbyauthorslider" class="tg-pickedbyauthor tg-pickedbyauthorslider owl-carousel">
                            @foreach ($weeklyBooks as $book)
                                <div class="item">
                                    <div class="tg-postbook">
                                        <figure class="tg-featureimg">
                                            <div class="tg-bookimg">
                                                <div class="tg-frontcover">
                                                    <img src="{{ $book->cover_picture }}" alt="{{ $book->title }}">
                                                </div>
                                            </div>
                                            <div class="tg-hovercontent">
                                                <div class="tg-description">
                                                    <p>{{ $book->description }}</p>
                                                </div>
                                                <strong class="tg-bookpage">Book Pages: {{ $book->pages }}</strong>
                                                <strong class="tg-bookcategory">Category:
                                                    {{ $book->category?->name }}</strong>
                                                <strong class="tg-bookprice">Genre:
                                                    @if ($book->genres->count() > 0)
                                                        @foreach ($book->genres as $genreItem)
                                                            {{ $genreItem->genre?->name }}
                                                        @endforeach
                                                    @endif
                                                </strong>
                                                <strong class="tg-bookwriter">Book Format: {{ ucwords(strtolower(str_replace('_', ' ', $book->ebook_format))) }}</strong>
                                            </div>
                                        </figure>
                                        <div class="tg-postbookcontent">
                                            <div class="tg-booktitle">
                                                <h3><a
                                                        href="{{ route('book', ['slug' => $book->slug]) }}">{{ $book->title }}</a>
                                                </h3>
                                            </div>
                                            <span class="tg-bookwriter">By: <a
                                                    href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a></span>
                                            <a class="tg-btn tg-btnstyletwo"
                                                href="{{ route('book', ['slug' => $book->slug]) }}">
                                                <em>Read</em>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection
