<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Ebook'],
    function () {
        Route::group(
            ['prefix' => 'ebook', 'as' => 'backend.ebook.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'BackendController@data']);
                Route::get('{ebook}/preview', ['as' => 'preview', 'uses' => 'BackendController@preview']);
                Route::post('{ebook}/quick_update', ['as' => 'quick_update', 'uses' => 'BackendController@quickUpdate']);
                Route::post(
                    '{ebook}/status/{status}',
                    ['as' => 'status', 'uses' => 'BackendController@status']
                );
            });
        Route::resource('ebook', 'BackendController');
    }
);
