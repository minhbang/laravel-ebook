<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Ebook', 'middleware' => config('ebook.middleware')],
    function () {
        Route::group(
            ['prefix' => 'ebook', 'as' => 'backend.ebook.'],
            function () {
                Route::get('status/{status}', ['as' => 'index_status', 'uses' => 'BackendController@index']);
                Route::get('data', ['as' => 'data', 'uses' => 'BackendController@data']);
                Route::post(
                    '{ebook}/quick_update',
                    ['as' => 'quick_update', 'uses' => 'BackendController@quickUpdate']
                );
                Route::post('{ebook}/status/{status}', ['as' => 'status', 'uses' => 'BackendController@status']);
            }
        );
        Route::resource('ebook', 'BackendController');
    }
);
