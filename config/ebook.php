<?php
return [
    // Hình bài tài liệu, lưu trong thư mục con của <app.paths.upload>
    'featured_image' => [
        'dir'       => 'images/ebooks',
        'width'     => 280,
        'height'    => 424,
        'width_md'  => 140,
        'height_md' => 212,
        'width_sm'  => 70,
        'height_sm' => 106,
        'method' => 'resize',
    ],
    'category'       => [
        'title'     => 'ebook::common.ebooks',
        'max_depth' => 5,
    ],

    'status_manager' => \Minhbang\Status\Managers\Simple::class,
    /**
     * Khai báo middleware cho Controller
     */
    'middleware'     => [ 'web', 'role:sys.admin' ],

    // Định nghĩa menus cho ebook
    'menus'          => [
        'backend.sidebar.content.ebook' => [
            'priority' => 4,
            'url'      => 'route:backend.ebook.index',
            'label'    => 'trans:ebook::common.ebooks',
            'icon'     => 'fa-book',
            'active'   => 'backend/ebook*',
        ],
    ],
];
