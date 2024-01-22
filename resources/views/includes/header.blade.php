<header id="tg-header" class="tg-header tg-headervtwo tg-haslayout">
    <div class="tg-topbar">
        <div class="container">
            <div class="row" style="padding-top: 5px !important">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <strong class="tg-logo"><a href="index.html"><img src="{{ asset('assets/images/logo.png') }}"
                                width="150px" alt="company name here"></a></strong>

                    <div class="tg-searchbox">
                        <form action="{{ route('books') }}" method="get" id="search_form"
                            class="tg-formtheme tg-formsearch">
                            <input type="hidden" name="sort" value="{{ isset($sortBy) ? $sortBy : '' }}">
                            <input type="hidden" name="order" value="{{ isset($orderBy) ? $orderBy : '' }}">
                            <input type="hidden" name="paginate"
                                value="{{ isset($paginationNo) ? $paginationNo : '' }}">

                            <fieldset>
                                <input type="text" name="keyword" value="{{ isset($searchTerm) ? $searchTerm : '' }}"
                                    class="typeahead form-control"
                                    placeholder="Search by title, author, category, ISBN...">
                                <button type="submit" class="tg-btn">Search</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tg-navigationarea">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-navigationholder">
                        <nav id="tg-nav" class="tg-nav">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#tg-navigation" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div id="tg-navigation" class="collapse navbar-collapse tg-navigation">
                                <ul>
                                    <li class="menu-item-has-children"><span class="tg-dropdowarrow">
                                            <i class="fa fa-angle-right"></i></span>
                                        <a href="javascript:void(0);">All Categories</a>
                                        <ul class="sub-menu">
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a
                                                        href="{{ route('books') . '?category=' . $category->id }}">{{ $category->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li class="@if (Route::currentRouteName() == 'home') current-menu-item @endif">
                                        <a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="@if (Route::currentRouteName() == 'books') current-menu-item @endif"><a
                                            href="{{ route('books') }}">Books</a></li>
                                    <li><a href="{{ route('downloads') }}">Downloads</a></li>
                                    <li><a href="{{ route('favorites') }}">Favorites</a></li>
                                    <li><a href="{{ route('reading-history') }}">Reading History</a></li>
                                    <li><a href="{{ route('borrowed') }}">Borrowed Books</a></li>
                                </ul>
                            </div>
                        </nav>
                        <div class="tg-wishlistandcart">
                            <div class="dropdown tg-themedropdown tg-currencydropdown">
                                <a href="javascript:void(0);" id="tg-currenty" class="tg-btnthemedropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Hi, {{ Auth::user()->student?->firstname }}</span>
                                </a>
                                <ul class="dropdown-menu tg-themedropdownmenu" aria-labelledby="tg-currenty">
                                    <li>
                                        <a href="{{ route('downloads') }}">
                                            Downloads
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('favorites') }}">
                                            Favorites
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('reading-history') }}">
                                            Reading History
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('borrowed') }}">
                                            Borrowed Books
                                        </a>
                                    </li>
                                    <li>
                                        <a onclick="event.preventDefault(); document.forms['logout-form'].submit()"
                                            style="cursor:pointer">Log Out</a>
                                        <form action="{{ route('logout') }}" id="logout-form" method="POST" hidden>
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
