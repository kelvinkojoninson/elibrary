@extends('layouts.app')
@section('page-name', 'Home')
@section('page-content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ asset('assets/images/parallax/bgparallax-07.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1>All Books</h1>
                        <ol class="tg-breadcrumb">
                            <li><a href="javascript:void(0);">home</a></li>
                            <li class="tg-active">Books</li>
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
                    <div id="tg-twocolumns" class="tg-twocolumns">
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9 pull-right">
                            <div id="tg-content" class="tg-content">
                                <div class="tg-products">
                                    <div class="tg-sectionhead">
                                        <h2><span>Student's Choice</span>Find Book</h2>
                                    </div>
                                    <div class="tg-productgrid">
                                        <div class="tg-refinesearch">
                                            <span>Showing {{ $books->firstItem() }} to {{ $books->lastItem() }} of
                                                {{ $books->total() }}</span>
                                            <form class="tg-formtheme tg-formsortshoitems">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <label>sort by:</label>
                                                        <span class="tg-select">
                                                            <select name="sort" class="sort_by">
                                                                <option value="created_at"
                                                                    {{ $sortBy == 'created_at' ? 'selected' : '' }}>
                                                                    Latest</option>
                                                                <option value="title"
                                                                    {{ $sortBy == 'title' ? 'selected' : '' }}>Title
                                                                </option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Order:</label>
                                                        <span class="tg-select">
                                                            <select name="order" class="order_by">
                                                                <option value="ASC"
                                                                    {{ $orderBy == 'ASC' ? 'selected' : '' }}>Ascending
                                                                </option>
                                                                <option value="DESC"
                                                                    {{ $orderBy == 'DESC' ? 'selected' : '' }}>Descending
                                                                </option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>show:</label>
                                                        <span class="tg-select">
                                                            <select name="paginationNo" class="paginate_by">
                                                                <option value="8"
                                                                    {{ $paginationNo == '8' ? 'selected' : '' }}>8</option>
                                                                <option value="12"
                                                                    {{ $paginationNo == '12' ? 'selected' : '' }}>12
                                                                </option>
                                                                <option value="16"
                                                                    {{ $paginationNo == '16' ? 'selected' : '' }}>16
                                                                </option>
                                                                <option value="20"
                                                                    {{ $paginationNo == '20' ? 'selected' : '' }}>20
                                                                </option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                        @foreach ($books as $book)
                                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
                                                <div class="tg-postbook">
                                                    <figure class="tg-featureimg">
                                                        <div class="tg-bookimg">
                                                            <div class="tg-frontcover">
                                                                <img src="{{ $book->cover_picture }}"
                                                                    alt="{{ $book->title }}">
                                                            </div>
                                                            <div class="tg-backcover">
                                                                <img src="{{ $book->cover_picture }}"
                                                                    alt="{{ $book->title }}">
                                                            </div>
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
                                                            <h3>
                                                                <a
                                                                    href="{{ route('book', ['slug' => $book->slug]) }}">{{ $book->title }}</a>
                                                            </h3>
                                                        </div>
                                                        <span class="tg-bookwriter">By:
                                                            <a
                                                                href="{{ route('books') . '?author=' . $book->author }}">{{ $book->author }}</a>
                                                        </span>
                                                        <span class="tg-bookwriter">Book Format: {{ ucwords(strtolower(str_replace('_', ' ', $book->ebook_format))) }}</span>
                                                        <a class="tg-btn tg-btnstyletwo"
                                                            href="{{ route('book', ['slug' => $book->slug]) }}">
                                                            <em>Start Reading</em>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{ $books->appends(['search' => $searchTerm, 'weeklyPick' => $weeklyPick, 'featured' => $featured, 'newRelease' => $newRelease, 'category' => $category, 'genre' => $genre, 'paginate' => $paginationNo, 'sort' => $sortBy, 'order' => $orderBy])->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 pull-left">
                            <aside id="tg-sidebar" class="tg-sidebar">
                                <div class="tg-widget tg-widgetsearch">
                                    <form action="{{ route('books') }}" method="get" class="tg-formtheme tg-formsearch">
                                        <div class="form-group">
                                            <button type="submit"><i class="icon-magnifier"></i></button>
                                            <input type="search" name="keyword"
                                                value="{{ isset($searchTerm) ? $searchTerm : '' }}" class="form-group"
                                                placeholder="Search by title, author, keyword...">
                                        </div>
                                    </form>
                                </div>
                                <div class="tg-widget tg-catagories">
                                    <div class="tg-widgettitle">
                                        <h3>Categories</h3>
                                    </div>
                                    <div class="tg-widgetcontent">
                                        <ul>
                                            @foreach ($categories as $categoryItem)
                                                <li>
                                                    <a href="{{ route('books') . '?category=' . $categoryItem->id }}">
                                                        <span>{{ $categoryItem->name }}</span>
                                                        <em>{{ number_format(count($categoryItem->books)) }}</em>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="tg-widget tg-catagories">
                                    <div class="tg-widgettitle">
                                        <h3>Book Genres</h3>
                                    </div>
                                    <div class="tg-widgetcontent">
                                        <ul>
                                            @foreach ($genres as $genreItem)
                                                <li>
                                                    <a href="{{ route('books') . '?genre=' . $genreItem->id }}">
                                                        <span>{{ $genreItem->name }}</span>
                                                        <em>{{ number_format(count($genreItem->books)) }}</em>
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li>
                                                <a href="{{ route('genres') }}">
                                                    <span>View All</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js-scripts')
    <script>
        jQuery(document).on('change', '.sort_by, .order_by, .paginate_by', function() {
            jQuery('input[name="sort"]').val(jQuery('.sort_by').val());
            jQuery('input[name="order"]').val(jQuery('.order_by').val());
            jQuery('input[name="paginate"]').val(jQuery('.paginate_by').val());
            jQuery('#search_form').submit();
        });
    </script>
@endpush
