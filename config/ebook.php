<?php
return [
    // Hình bài tài liệu, lưu trong thư mục con của <upload_path>
    'featured_image'     => [
        'dir'       => 'images/ebooks',
        'width'     => 280,
        'height'    => 424,
        'width_md'  => 140,
        'height_md' => 212,
        'width_sm'  => 70,
        'height_sm' => 106,
    ],
    /**
     * Thư mục chứa file Tài liệu số
     * Là thư mục con của <root>/storage/data
     */
    'data_dir'           => 'ebooks',
    /**
     * Tự động add các route
     */
    'add_route'          => true,
    /**
     * Khai báo middleware cho Controller
     */
    'middleware'         => 'role:sys.admin',
    /**
     * Status manager
     */
    'status_manager'     => \Minhbang\ILib\EbookStatus::class,
    'category_max_depth' => 5,

    // Định nghĩa menus cho ebook
    'menus'              => [
        'backend.sidebar.content.ebook' => [
            'priority' => 4,
            'url'      => 'route:backend.ebook.index',
            'label'    => 'trans:ebook::common.ebooks',
            'icon'     => 'fa-book',
            'active'   => 'backend/ebook*',
        ],
    ],
];
