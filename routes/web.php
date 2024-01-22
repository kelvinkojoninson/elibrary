<?php

use App\Http\Controllers\RouteController;
use App\Models\Books;
use App\Models\DownloadedBooks;
use App\Models\FavoriteBooks;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [RouteController::class, 'home'])->name('home');
Route::get('/books', [RouteController::class, 'books'])->name('books');
Route::get('/book/{slug}', [RouteController::class, 'book'])->name('book');
Route::get('/account', [RouteController::class, 'account'])->name('account');
Route::get('/genres', [RouteController::class, 'genres'])->name('genres');
Route::get('/downloads', [RouteController::class, 'downloads'])->name('downloads');
Route::get('/favorites', [RouteController::class, 'favorites'])->name('favorites');
Route::get('/reading-history', [RouteController::class, 'readingHistory'])->name('reading-history');
Route::get('/borrowed', [RouteController::class, 'borrowedBooks'])->name('borrowed');
Route::get('/delete-download/{id}', function ($id) {
    try {
        $book = DownloadedBooks::where('book_id', $id)
            // ->where('student_id', Auth::user()->userid)
            ->first();
        if (!$book) {
            return redirect('/downloads')->with('error', 'Removing book failed! Please try again.');
        }

        $book->delete();
        return redirect('/downloads')->with('success', 'Book removed successfully.');
    } catch (\Exception $e) {
        return redirect('/downloads')->with('error', 'Removing book failed! Please try again.');
    }
})->name('delete.download');

Route::get('/delete-favorite/{id}', function ($id) {
    try {
        $book = FavoriteBooks::where('book_id', $id)
            // ->where('student_id', Auth::user()->userid)
            ->first();
        if (!$book) {
            return redirect('/favorites')->with('error', 'Removing book failed! Please try again.');
        }

        $book->delete();
        return redirect('/favorites')->with('success', 'Book removed successfully.');
    } catch (\Exception $e) {
        return redirect('/favorites')->with('error', 'Removing book failed! Please try again.');
    }
})->name('delete.favorite');

Route::get('/add-favorite/{id}', function ($id) {
    try {
        $book = FavoriteBooks::create([
            'book_id' => $id,
            'student_id' =>  Auth::user()->userid,
        ]);
        return back()->with('success', 'Book added to favorite successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Adding book to favorite failed.']);
    }
})->name('add.favorite');

Route::get('/remove-favorite/{id}', function ($id) {
    try {
        $book = FavoriteBooks::where('book_id', $id)->where('student_id', Auth::user()->userid)->delete();

        return back()->with('success', 'Book removed to favorite successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Removing book to favorite failed.']);
    }
})->name('remove.favorite');

Route::get('/download-book/{id}', function ($id) {
    try {
        $book = Books::where('book_id', $id)->first();
        DownloadedBooks::firstOrCreate([
            'book_id' => $id,
            'student_id' =>  Auth::user()->userid,
            'term' => env('ACADEMIC_YEAR'),
        ]);
        return back()->with('success', 'Book downloaded successfully. Please check your downloads to save the file to your device.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Downloading book failed.']);
    }
})->name('download.book');


require __DIR__ . '/auth.php';
