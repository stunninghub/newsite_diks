<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPostController as PostCnt;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    $all_posts = PostCnt::get_posts();
    return view('posts', compact('all_posts'));
});

Route::any('/add_post', [PostCnt::class, 'create']);
Route::any('/get_post_data', [PostCnt::class, 'getpost_data']);
Route::any('/delete_post', [PostCnt::class, 'delete']);
