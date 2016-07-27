<?php
return [
    // Hình bài tài liệu, lưu trong thư mục con của <upload_path>
    'featured_image' => [
        'dir'       => 'images/ebooks',
        'width'     => 280,
        'height'    => 424,
        'width_md'  => 140,
        'height_md' => 212,
        'width_sm'  => 70,
        'height_sm' => 106,
    ],
    'category'       => [
        'title'     => 'ebook::common.ebooks',
        'max_depth' => 5,
    ],
    /**
     * Thư mục chứa file Tài liệu số
     * Là thư mục con của <root>/storage/data
     */
    'data_dir'       => 'ebooks',
    /**
     * Tự động add các route
     */
    'add_route'      => true,
    /**
     * Khai báo middleware cho Controller
     */
    'middleware'     => 'role:sys.admin',
    'datatable'      => \Minhbang\Ebook\Datatable::class,
    'html'           => \Minhbang\Ebook\Html::class,
    'access_control' => \Minhbang\Ebook\EbookAccessControl::class,

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
