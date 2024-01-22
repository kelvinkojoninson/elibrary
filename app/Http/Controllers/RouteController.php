<?php

namespace App\Http\Controllers;

use App\Models\BookCategories;
use App\Models\Books;
use App\Models\BorrowedBooks;
use App\Models\DownloadedBooks;
use App\Models\FavoriteBooks;
use App\Models\Genres;
use App\Models\ReadingHistory;
use App\Services\MiscService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    private $miscService;  // Private property to store the MiscService instance
    private $categories;

    public function __construct(MiscService $miscService)
    {
        $this->middleware(["auth", "checkLogin"]);  // Apply middleware for authentication and verification
        $this->miscService = $miscService;
        $this->categories = BookCategories::orderBy('name')->get();
    }

    public function home()
    {
        $books = Books::query();

        return view("modules.home.index",  [
            "categories" => $this->categories,
            "featuredBooks" => $books->where('is_ebook', 'YES')->where('featured', 'YES')->whereNotNull('cover_picture')->get(),
            "weeklyBooks" => $books->where('is_ebook', 'YES')->where('weekly_pick', 'YES')->whereNotNull('cover_picture')->get(),
            "newReleaseBooks" => $books->where('is_ebook', 'YES')->where('new_release', 'YES')->whereNotNull('cover_picture')->get()
        ]);
    }

    public function books(Request $request)
    {
        $sortBy = $request->input('sort', 'created_at');
        $paginationNo =  $request->input('paginate', 12);
        $orderBy = $request->input('order', 'DESC');
        $searchTerm = $request->input('keyword');
        $weeklyPick = $request->input('weekly_pick');
        $featured = $request->input('featured');
        $newRelease = $request->input('new_release');
        $genre = $request->input('genre');
        $category = $request->input('category');
        $author = $request->input('author');

        $query = Books::where('is_ebook', 'YES')->where('status', 'ACTIVE')->whereNotNull('cover_picture');

        // Add search conditions if search term is provided
        $query->when($searchTerm, function ($q) use ($searchTerm) {
            $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('category', function ($subquery) use ($searchTerm) {
                    $subquery->where('name', 'LIKE', '%' . $searchTerm . '%');
                });
        });

        $query->when($weeklyPick, function ($q) use ($weeklyPick) {
            $q->where('weekly_pick', 'YES');
        });

        $query->when($featured, function ($q) use ($featured) {
            $q->where('featured', 'YES');
        });

        $query->when($newRelease, function ($q) use ($newRelease) {
            $q->where('new_release', 'YES');
        });

        $query->when($category, function ($q) use ($category) {
            $q->where('category_id', $category);
        });

        $query->when($author, function ($q) use ($author) {
            $q->where('author','like', '%'.$author.'%');
        });

        $query->when($genre, function ($q) use ($genre) {
            $q->whereHas('genres', function ($subquery) use ($genre) {
                $subquery->where('genre_id', 'LIKE', '%' . $genre . '%');
            });
        });

        $books = $query->orderBy($sortBy ?: 'created_at', $orderBy ?: 'DESC')->paginate($paginationNo);

        $genres = Genres::withCount(['books'])->having('books_count', '>', 0)
            ->orderByDesc('books_count')
            ->take(10)->get();

        return view("modules.books.index",  [
            "categories" => $this->categories,
            "genres" => $genres,
            "books" => $books,
            "weeklyPick" => $weeklyPick,
            "featured" => $featured,
            "newRelease" => $newRelease,
            "category" => $category,
            "genre" => $genre,
            "searchTerm" => $searchTerm,
            "sortBy" => $sortBy,
            "orderBy" => $orderBy,
            "paginationNo" => $paginationNo
        ]);
    }

    public function book($slug)
    {
        $book = Books::where('slug', $slug)->firstOrFail();
        $similarBooks = Books::where('is_ebook', 'YES')->where('category_id', $book->category_id)->whereNotNull('cover_picture')->inRandomOrder()->take(20)->get();
        $isFavorite = FavoriteBooks::where('book_id', $book->book_id)->where('student_id', Auth::user()->userid)->first();
        $isDownloaded = DownloadedBooks::where('book_id', $book->book_id)->where('student_id', Auth::user()->userid)->first();
        $chkAcademicDownload = DownloadedBooks::where('student_id', Auth::user()->userid)->where('term', env('ACADEMIC_YEAR'))->first();
        
       ReadingHistory::firstOrCreate([
            'book_id' => $book->book_id,
            'student_id' =>  Auth::user()->userid,
            'start_time' => date('Y-m-d H:i:s')
        ]);

        return view("modules.book.index",  [
            "categories" => $this->categories,
            "book" => $book,
            "similarBooks" => $similarBooks,
            "isFavorite" => $isFavorite,
            "isDownloaded" => $isDownloaded,
            "chkAcademicDownload" => $chkAcademicDownload
        ]);
    }

    public function downloads()
    {
        $downloads = DownloadedBooks::where('student_id', Auth::user()->userid)->pluck('book_id')->all();
        $books = Books::whereIn('book_id', $downloads)->where('is_ebook', 'YES')->whereNotNull('cover_picture')->paginate(20);

        return view("modules.downloads.index",  [
            "categories" => $this->categories,
            "books" => $books
        ]);
    }

    public function favorites()
    {
        $downloads = FavoriteBooks::where('student_id', Auth::user()->userid)->pluck('book_id')->all();
        $books = Books::whereIn('book_id', $downloads)->where('is_ebook', 'YES')->whereNotNull('cover_picture')->paginate(20);

        return view("modules.favorites.index",  [
            "categories" => $this->categories,
            "books" => $books
        ]);
    }

    public function readingHistory()
    {
        $downloads = ReadingHistory::where('student_id', Auth::user()->userid)->pluck('book_id')->all();
        $books = Books::whereIn('book_id', $downloads)->where('is_ebook', 'YES')->whereNotNull('cover_picture')->paginate(20);

        return view("modules.history.index",  [
            "categories" => $this->categories,
            "books" => $books
        ]);
    }

    public function borrowedBooks()
    {
        $books = BorrowedBooks::where('student_id', Auth::user()->userid)->whereHas('book')->paginate(20);
     
        return view("modules.borrowed.index",  [
            "categories" => $this->categories,
            "books" => $books
        ]);
    }

    public function profile()
    {
        $deleteReasons = [
            'I dont find it useful',
            'I dont understand how it works',
            'I have safety concerns',
            'I have privacy concerns',
            'Created another account',
            'Just need a break',
            'App crushes too often',
            'Something Else'
        ];

        return view('modules.profile.index', [
            "permissions" => $this->miscService->permissions(Auth::user()->role_id),
            "deleteReasons" => $deleteReasons,
        ]);
    }
}
