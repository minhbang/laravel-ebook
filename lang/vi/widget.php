<?php
return [
    'ebook_category' => [
        'title' => 'Danh mục Ebook',
        'description' => 'Hiển thị Danh sách Danh mục Ebook',
    ],
    'latest_ebooks' => [
        'title' => 'Danh sách Ebook',
        'description' => 'Hiển thị danh sách Ebook mới nhất',
        'category_id' => 'Danh mục',
        'show_link_category' => 'Link đến Danh mục',
        'limit' => 'Số Ebook hiển thị',
        'item_style' => 'Layout',
        'item_styles' => [
            'th' => 'Grid',
            'list' => 'List',
        ],
        'item_css' => 'Css các item',
        'item_css_hint' => 'Phân các bằng dấu <code>|</code>, hết sẽ lặp lại, vd: <code>col-md-4 wow fadeInLeft|col-md-4 wow zoomIn|col-md-4 wow fadeInRight</code>',
    ],
    'slider_ebooks' => [
        'title' => 'Ebook slider',
        'description' => 'Hiển thị slider Ebook',
        'category_id' => 'Danh mục',
        'limit' => 'Số Ebook hiển thị',
        'query_type' => 'Lấy ebook',
        'query_types' => [
            'featured' => 'Đặc sắc',
            'latest' => 'Mới nhất',
        ],
    ],
];