<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', function()
{
	return View::make('hello');
});

Route::post('/api/auth/login', function() {
    $email = Input::get('email');
    $password = Input::get('password');

    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        $user = Auth::user();
        return Response::json($user->toApiArray(), 200, [], JSON_NUMERIC_CHECK);
    } else {
        return Response::json(['error' => 'Invalid email or password.'], 403);
    }
});

Route::post('/api/auth/logout', function() {
    if (Auth::check()) {
        Auth::logout();
        return Response::json([]);
    }

    return Response::json(['error' => 'Not logged in.'], 403);
});

Route::options('/api/{path?}', function() {
    return Response::make('', 200, [
        // 'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Headers' => 'Content-Type',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
    ]);
})->where('path', '.+');

Route::group(['prefix' => '/api', 'before' => 'auth', 'after' => 'cors'], function() {
    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::get('/logi/secretKey', ['uses' => 'LogiController@secretKey']);
    Route::get('/crm/token', ['uses' => 'CrmController@token']);

    Route::get('/payeePayment/unpaidPayeePayments', ['uses' => 'PayeePaymentController@unpaidPayeePayments']);

    Route::post('/{model}', ['as' => 'api.entity', 'uses' => 'ApiController@createEntity']);
    Route::put('/{model}/{id}', ['as' => 'api.entity', 'uses' => 'ApiController@updateEntity']);
    Route::delete('/{model}/{id}', ['as' => 'api.entity', 'uses' => 'ApiController@deleteEntity']);
    Route::get('/{model}/{id}/method/{method}', ['as' => 'api.entity', 'uses' => 'ApiController@method']);
    Route::get('/{model}/method/{method}', ['as' => 'api.entity', 'uses' => 'ApiController@methodNoId']);
    Route::get('/royalty_stream_files/{id}/pdf', ['as' => 'api.royalty_stream_files.pdf', 'uses' => 'ApiController@royalty_stream_file_pdf']);
    Route::get('/{model}', ['as' => 'api.collection', 'uses' => 'ApiController@collection']);
    Route::get('/{model}/{id}', ['as' => 'api.entity', 'uses' => 'ApiController@entity']);
    Route::get('/{model}/{id}/{relation}', ['as' => 'api.entity.relation', 'uses' => 'ApiController@relation'])->where('id', '[0-9]+');
    Route::post('/{model}/upload', ['uses' => 'ApiController@uploadEntity']);
    Route::get('/{model}/download/{downloadMethod}', ['uses' => 'ApiController@downloadNoId']);
    Route::get('/{model}/{id}/download/{downloadMethod}', ['uses' => 'ApiController@download']);
    Route::post('/{model}/method/{method}', ['as' => 'api.entity', 'uses' => 'ApiController@methodNoId']);

});
