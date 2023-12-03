<?php

use App\Enums\Tenum;
use App\Http\Controllers\MovimentacaoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/info', function (Request $request) {
    $var = 25;
    response()->json($var);
});

Route::get('/enum/{enumClass}', [Tenum::class, 'getEnumValues']);

Route::any('/movimentacao', [MovimentacaoController::class, 'handle']);
Route::any('/movimentacao/file', [MovimentacaoController::class, 'getImportar']);
Route::any('/movimentacao/importar', [MovimentacaoController::class, 'importar']);
