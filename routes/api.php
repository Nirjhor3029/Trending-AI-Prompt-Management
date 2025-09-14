<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {

    Route::get('test', function () {
        return response()->json(
            ['message' => 'Success']
        );
    });

    // Prompt
    Route::post('prompts/media', 'PromptApiController@storeMedia')->name('prompts.storeMedia');
    Route::apiResource('prompts', 'PromptApiController');

    // Ai Platform
    Route::apiResource('ai-platforms', 'AiPlatformApiController');

    // Page
    Route::apiResource('pages', 'PageApiController');
});
