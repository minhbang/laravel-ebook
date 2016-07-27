<?php namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\Datatable as BaseDatatable;
use Html as HtmlBuilder;

/**
 * Class Datatable
 * Quản lý Datatable của ebook
 *
 * @package Minhbang\Ebook
 */
class Datatable extends BaseDatatable
{
    /**
     * @return array
     */
    function columns()
    {
        return [
            'index'          => [
                'title' => '#',
                'data'  => '',
            ],
            'featured_image' => [
                'title' => trans('ebook::common.featured_image'),
                'data'  => function (Ebook $model) {
                    return $model->present()->featured_image_lightbox;
                },
            ],
            'ebook'          => [
                'title' => trans('ebook::common.ebook'),
                'data'  => function (Ebook $model) {
                    return $model->present()->title_block;
                },
            ],
            'security'       => [
                'title' => trans('ebook::common.security_id'),
                'data'  => function (Ebook $model) {
                    return $model->present()->securityFormated;
                },
            ],
            'actions'        => [
                'title' => trans('common.actions'),
                'data'  => function (Ebook $model) {
                    /** @var \Minhbang\Category\Type $categoryManager */
                    $url = route($this->controller->route_prefix . 'backend.ebook.status', ['ebook' => $model->id, 'status' => 'STATUS']);
                    /*$statuses = $this->controller->allStatus ?
                        $model->present()->status($url) . '<br>' : $model->present()->statusActions($url);*/
                    //TODO: statuses buttons
                    $statuses = '';

                    return $statuses . HtmlBuilder::tableActions(
                        $this->controller->route_prefix . 'backend.ebook',
                        ['ebook' => $model->id],
                        $model->title,
                        trans('ebook::common.ebook'),
                        [
                            'renderPreview' => 'link',
                            'renderEdit'    => $model->allowed(user(), 'update') ? 'link' : 'disabled',
                            'renderDelete'  => $model->allowed(user(), 'delete') ? 'link' : 'disabled',
                            'renderShow'    => 'link',
                        ]
                    );
                },
            ],
        ];
    }

    /**
     * @return array
     */
    function zones()
    {
        return [
            'backend' => [
                'table'   => [
                    'id'        => 'ebook-manage',
                    'class'     => 'table-ebooks',
                    'row_index' => true,
                ],
                'options' => [
                    'aoColumnDefs' => [
                        ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                        ['sClass' => 'min-width', 'aTargets' => [1]],
                        ['sClass' => 'min-width text-right', 'aTargets' => [-1, -2]],
                    ],
                ],
                'columns' => ['index', 'featured_image', 'ebook', 'security', 'actions'],
                'search'  => 'ebooks.title',
            ],
        ];
    }
}