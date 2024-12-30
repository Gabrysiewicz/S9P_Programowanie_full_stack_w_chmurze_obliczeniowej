<?php

use App\Models\Offert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OffertController;
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

Route::get('/', [OffertController::class, 'index']);

Route::get('/offerts/create', [OffertController::class, 'create'])->middleware('auth');
Route::post('/offerts', [OffertController::class, 'store'])->middleware('auth');

Route::get('/offerts/{offert}/edit', [OffertController::class, 'edit'])->middleware('auth');
Route::put('/offerts/{offert}', [OffertController::class, 'update'])->middleware('auth');

Route::get('/offerts/manage', [OffertController::class, 'manage'])->middleware('auth');

Route::delete('/offerts/{offert}', [OffertController::class, 'delete'])->middleware('auth');
Route::get('/offerts/{offert}', [OffertController::class, 'show']);


Route::get('/register', [UserController::class, 'create'])->middleware('guest');
Route::post('/users', [UserController::class, 'store']);

Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
// Route::get('/offert/{id}', function ($id) {
//     $listing = Offert::find($id);
//     if($listing){
//         return view('offert', [
//             'listing' => $listing
//         ]);
//     }else{
//         abort('404');
//     }
// });

// Route::get('/search', function (Request $request) {
//     return "<p>{$request->name} {$request->surname}</p>";
// });

