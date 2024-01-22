@extends('layouts.app')
@section('page-name', $book->title)
@section('page-content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ asset('assets/images/parallax/bgparallax-07.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1>{{ $book->title }}</h1>
                        <ol class="tg-breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('books') }}">Books</a></li>
                            <li class="tg-active">{{ $book->title }}</li>
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
                    <div id="tg-twocolumns" class="tg-twocolumns">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-right">
                            <div id="tg-content" class="tg-content">
                                <div class="tg-productdetail">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="tg-postbook">
                                                <figure class="tg-featureimg">
                                                    <img src="{{ $book->cover_picture }}" alt="{{ $book->title }}">
                                                </figure>
                                                <div class="tg-postbookcontent">
                                                    @if ($isFavorite)
                                                        <a class="tg-btn tg-active tg-btn-lg"
                                                            href="{{ route('remove.favorite', ['id' => $book->book_id]) }}">Remove
                                                            From
                                                            Favorite</a>
                                                    @else
                                                        <a class="tg-btn tg-active tg-btn-lg"
                                                            href="{{ route('add.favorite', ['id' => $book->book_id]) }}">Add
                                                            To
                                                            Favorite</a>
                                                    @endif
                                                    @if (!$chkAcademicDownload)
                                                        <a class="tg-btnaddtowishlist" href="{{ route('download.book', ['id' => $book->book_id]) }}">
                                                            <span>Download</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="tg-productcontent">
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
                                                    <h3>{{ $book->title }}</h3>
                                                </div>
                                                <span class="tg-bookwriter">By:
                                                    <a
                                                        href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a>
                                                </span>

                                                <div class="tg-description" style="margin-top: 20px">
                                                    <p>{{ $book->description }}</p>
                                                </div>
                                                <div class="tg-sectionhead" style="margin-top: 20px">
                                                    <h2>Book Details</h2>
                                                </div>
                                                <ul class="tg-productinfo">
                                                    <li><span>Format:</span><span>{{ ucwords(strtolower(str_replace('_', ' ', $book->ebook_format))) }}</span>
                                                    </li>
                                                    <li><span>Pages:</span><span>{{ $book->pages }}</span></li>
                                                    <li><span>Publication Year:</span><span>{{ $book->year }}</span></li>
                                                    <li><span>Publisher:</span><span>{{ $book->publisher }}</span></li>
                                                    <li><span>Language:</span><span>{{ $book->language }}</span></li>
                                                    <li><span>Country:</span><span>{{ $book->country }}</span></li>
                                                    <li><span>ISBN:</span><span>{{ $book->isbn }}</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tg-productdescription">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <ul class="tg-themetabs" role="tablist">
                                                    @if ($book->ebook_format == 'PDF' || $book->ebook_format == 'EPUB')
                                                        <li role="presentation" class="active"><a href="#pdf"
                                                                data-toggle="tab">Read Book</a></li>
                                                    @endif
                                                    @if ($book->ebook_format == 'VIDEO')
                                                        <li role="presentation"><a href="#video"
                                                                data-toggle="tab">Watch</a></li>
                                                    @endif
                                                    @if ($book->ebook_format == 'AUDIO_BOOK')
                                                        <li role="presentation"><a href="#audio"
                                                                data-toggle="tab">Listen</a></li>
                                                    @endif
                                                </ul>
                                                <div class="tg-tab-content tab-content">
                                                    @if ($book->ebook_format == 'PDF' || $book->ebook_format == 'EPUB')
                                                        <div role="tabpanel" class="tg-tab-pane tab-pane active"
                                                            id="pdf">
                                                            <div class="tg-description">
                                                                <object data="{{ $book->filepath }}#toolbar=0"
                                                                    type="application/pdf" width="100%" height="1000px">
                                                                    <iframe src="{{ $book->filepath }}#toolbar=0"
                                                                        width="100%" height="1000px" style="border: none">
                                                                        <p>
                                                                            Your browser does not support PDFs.
                                                                            <a href="{{ $book->filepath }}">Download the
                                                                                PDF</a>
                                                                        </p>
                                                                    </iframe>
                                                                </object>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($book->ebook_format == 'VIDEO')
                                                        <div role="tabpanel" class="tg-tab-pane tab-pane active"
                                                            id="video">
                                                            <div class="tg-description">
                                                                <video controls controlsList="nodownload" width="100%"
                                                                    height="600" poster="{{ $book->cover_picture }}">
                                                                    <source
                                                                        src="https://www.youtube.com/embed/aLwpuDpZm1k?rel=0&amp;controls=0&amp;showinfo=0"
                                                                        type="video/mp4">
                                                                    Your browser does not support the video element.
                                                                </video>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($book->ebook_format == 'AUDIO_BOOK')
                                                        <div role="tabpanel" class="tg-tab-pane tab-pane active"
                                                            id="audio">
                                                            <div class="tg-description">
                                                                <audio controls controlsList="nodownload" width="100%">
                                                                    <source src="{{ $book->filepath }}" type="audio/mp3">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tg-relatedproducts">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="tg-sectionhead">
                                                    <h2><span>Similar Books</span>You May Also Like</h2>
                                                    <a class="tg-btn"
                                                        href="{{ route('books') . '?category=' . $book->category_id }}">View
                                                        All</a>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div id="tg-relatedproductslider"
                                                    class="tg-relatedproductslider tg-relatedbooks owl-carousel">
                                                    @foreach ($similarBooks as $similarBookItem)
                                                        <div class="item">
                                                            <div class="tg-postbook">
                                                                <figure class="tg-featureimg">
                                                                    <div class="tg-bookimg">
                                                                        <div class="tg-frontcover"><img
                                                                                src="{{ $similarBookItem->cover_picture }}"
                                                                                alt="{{ $similarBookItem->title }}"></div>
                                                                        <div class="tg-backcover"><img
                                                                                src="{{ $similarBookItem->cover_picture }}"
                                                                                alt="{{ $similarBookItem->title }}"></div>
                                                                    </div>
                                                                    <a class="tg-btnaddtowishlist"
                                                                        href="{{ route('book', ['slug' => $similarBookItem->slug]) }}">
                                                                        <span>Start Reading</span>
                                                                    </a>
                                                                </figure>
                                                                <div class="tg-postbookcontent">
                                                                    <ul class="tg-bookscategories">
                                                                        @if ($similarBookItem->genres->count() > 0)
                                                                            @foreach ($similarBookItem->genres as $genreItem)
                                                                                <li><a
                                                                                        href="{{ route('books') . '?genre=' . $genreItem->genre_id }}">{{ $genreItem->genre?->name }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>
                                                                    <div class="tg-themetagbox">
                                                                        <span class="tg-themetag">
                                                                            <a style="color: #ffffff"
                                                                                href="{{ route('books') . '?category=' . $similarBookItem->category_id }}">{{ ucfirst($similarBookItem->category?->name) }}</a>
                                                                        </span>
                                                                    </div>
                                                                    <div class="tg-booktitle">
                                                                        <h3><a
                                                                                href="{{ route('book', ['slug' => $similarBookItem->slug]) }}">{{ $similarBookItem->title }}</a>
                                                                        </h3>
                                                                    </div>
                                                                    <span class="tg-bookwriter">By: <a
                                                                            href="{{ route('books') . '?author=' . $similarBookItem->author }}">{{ $similarBookItem->author }}</a></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
