<?php

use App\Http\Controllers\ContactoController;
use App\Http\Controllers\Oath\GitHubController;
use App\Http\Livewire\ShowUserPosts;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $posts=Post::where('status', 2)->orderBy('id', 'DESC')->paginate(5);
    return view('dashboard', compact('posts'));
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('userposts', ShowUserPosts::class)->name('posts.index');


//rutas para el formulario de contacto
Route::middleware(['auth:sanctum', 'verified'])->get('contacto', [ContactoController::class, 'pintarFormulario'])->name('contacto.pintar');
Route::middleware(['auth:sanctum', 'verified'])->post('contacto', [ContactoController::class, 'procesarFormulario'])->name('contacto.procesar');
//Rutas para oath de github
Route::get('auth/github/redirect', [GitHubController::class, 'redirect'])
    ->name('github.redirect');
Route::get('auth/github/callback', [GitHubController::class, 'callback'])
    ->name('github.callback');
