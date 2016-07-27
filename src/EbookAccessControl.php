<?php
namespace Minhbang\Ebook;

use Minhbang\Security\AccessControl;
use Minhbang\User\User;

/**
 * Class AccessControl
 *
 * @package Minhbang\Ebook
 */
class EbookAccessControl extends AccessControl
{
    /**
     * Mới tải lên: ebook do bạn đọc upload lên
     */
    const STATUS_UPLOADED = 1;
    /**
     * Đang biên mục: chỉ nhân viên thư viện được phép xem, Mặc định
     */
    const STATUS_EDITING = 2;
    /**
     * Chờ duyệt: chỉ nhân viên và phụ trách thư viện được xem
     */
    const STATUS_REVIEWING = 3;
    /**
     * Đã xuất bản: được phép xem
     */
    const STATUS_PUBLISHED = 4;

    /**
     * @return array
     */
    protected function defineStatuses()
    {
        return [
            static::STATUS_UPLOADED  => [
                'title' => trans('ilib::status.uploaded'),
                'can'   => [
                    'read'   => false,
                    'update' => true,
                    'delete' => true,
                    'set'    => [
                        static::STATUS_UPLOADED => true,
                    ],
                ],
            ],
            static::STATUS_EDITING   => [
                'title'   => trans('ilib::status.editing'),
                'can'     => [
                    'read'   => false,
                    'update' => true,
                    'delete' => true,
                    'set'    => [
                        static::STATUS_EDITING => true,
                    ],
                ],
                'editing' => true,
            ],
            static::STATUS_REVIEWING => [
                'title' => trans('ilib::status.reviewing'),
                'can'   => [
                    'read'   => false,
                    'update' => true,
                    'delete' => true,
                    'set'    => [
                        static::STATUS_PUBLISHED => function (User $user) {
                            return $user->hasRole('tv.pt');
                        },
                    ],
                ],
            ],
            static::STATUS_PUBLISHED => [
                'title'     => trans('ilib::status.published'),
                'can'       => [
                    'read'   => true,
                    'update' => false,
                    'delete' => false,
                    'set'    => [
                        static::STATUS_EDITING => function (User $user) {
                            return $user->hasRole('tv.pt');
                        },
                    ],
                ],
                'published' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function defineLevels()
    {
        return [];
    }
}