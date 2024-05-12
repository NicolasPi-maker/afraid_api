<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\SpeechController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\TeaserController;
use App\Http\Middleware\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//LOGIN ROUTES
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh']);

////PASSWORD RESET ROUTES
//Route::get('reset-password/{token}', [ResetPasswordController::class, 'show']);
//Route::post('reset-password', [ResetPasswordController::class, 'insert']);
//Route::patch('reset-password/{email}', [ResetPasswordController::class, 'update']);

//AUTHENTICATED ROUTE
Route::middleware(JWTAuth::class)->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

// LANGUAGE ROUTES
Route::get('/languages', [LanguageController::class, 'index']);

// PROMPT ROUTES
Route::post('/prompt/random', [PromptController::class, 'generateRandomPrompt']);

// STORY ROUTES
Route::post('/story', [StoryController::class, 'insert']);
Route::get('/story/{id}', [StoryController::class, 'show']);

// TEASER ROUTES
Route::post('/teaser', [TeaserController::class, 'insert']);
Route::post('/teaser/generate', [TeaserController::class, 'generate']);

// CHAPTER ROUTES
Route::post('/chapter/{storyId}', [ChapterController::class, 'insert']);

// SPEECH ROUTES
Route::post('/speech', [SpeechController::class, 'insert']);
