<?php
namespace Minhbang\Ebook;

use Minhbang\LaravelKit\Extensions\BackendController as BaseController;
use Minhbang\Ebook\Request as EbookRequest;
use Minhbang\LaravelKit\Traits\Controller\QuickUpdateActions;
use Session;
use Request;
use Datatable;
use Minhbang\LaravelKit\Support\VnString;
use Html;

/**
 * Class Controller
 *
 * @package Minhbang\Ebook
 */
class BackendController extends BaseController
{
    use QuickUpdateActions;
    /**
     * @var \Minhbang\Category\Manager
     */
    protected $categoryManager;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = app('category')->manage('ebook');
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tableOptions = [
            'id'        => 'ebook-manage',
            'class'     => 'table-ebooks',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                ['sClass' => 'min-width', 'aTargets' => [1, -1]],
                ['sClass' => 'min-width text-right', 'aTargets' => [-2, -3]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '',
                trans('ebook::common.featured_image'),
                trans('ebook::common.ebook'),
                trans('ebook::common.security_id'),
                trans('ebook::common.status'),
                trans('common.actions')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $name = trans('ebook::common.ebooks');
        $this->buildHeading([trans('common.manage'), $name], 'fa-file-pdf-o', ['#' => $name]);

        return view('ebook::backend.index', compact('tableOptions', 'options', 'table'));
    }


    /**
     * Danh sách Ebook theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\Ebook\Ebook $query */
        $query = Ebook::queryDefault()->withEnumTitles()->orderUpdated();
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('ebooks.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('ebooks.updated_at', 'mb_date_vn2mysql');
        }

        return Datatable::query($query)
            ->addColumn(
                'index',
                function (Ebook $model) {
                    return $model->id;
                }
            )
            ->addColumn(
                'image',
                function (Ebook $model) {
                    return $model->present()->featured_image_lightbox;
                }
            )
            ->addColumn(
                'title',
                function (Ebook $model) {
                    return $model->present()->title_block;
                }
            )
            ->addColumn(
                'security',
                function (Ebook $model) {
                    return $model->present()->securityFormated;
                }
            )
            ->addColumn(
                'status',
                function (Ebook $model) {
                    return $model->present()->status;
                }
            )
            ->addColumn(
                'actions',
                function (Ebook $model) {
                    return Html::tableActions(
                        $this->route_prefix . 'backend.ebook',
                        ['ebook' => $model->id],
                        $model->title,
                        trans('ebook::common.ebook'),
                        [
                            'renderPreview' => 'link',
                            'renderEdit'    => 'link',
                            'renderShow'    => 'link',
                        ]
                    );
                }
            )
            ->searchColumns('ebooks.title')
            ->make();
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create()
    {
        $ebook = new Ebook();
        $url = route($this->route_prefix . 'backend.ebook.store');
        $method = 'post';
        $categories = $this->categoryManager->selectize();
        $this->buildHeading(
            [trans('common.create'), trans('ebook::common.ebooks')],
            'plus-sign',
            [
                route($this->route_prefix . 'backend.ebook.index') => trans('ebook::common.ebook'),
                '#' => trans('common.create')
            ]
        );
        $file_hint = trans('ebook::common.file_hint_create');

        return view(
            'ebook::backend.form',
            compact('ebook', 'url', 'method', 'categories', 'file_hint') + $ebook->loadEnums()
        );
    }

    /**
     * @param \Minhbang\Ebook\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(EbookRequest $request)
    {
        $ebook = new Ebook();
        $ebook->fill($request->all());
        $ebook->fillFeaturedImage($request);
        $ebook->fileFill($request);
        $ebook->user_id = user('id');
        $ebook->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.create_object_success', ['name' => trans('ebook::common.ebooks')]),
            ]
        );

        return redirect(route($this->route_prefix . 'backend.ebook.index'));
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function show(Ebook $ebook)
    {
        $name = trans('ebook::common.ebooks');
        $this->buildHeading(
            [trans('common.view_detail'), $name],
            'list',
            [route($this->route_prefix . 'backend.ebook.index') => $name, '#' => trans('common.view_detail')],
            [
                [
                    route($this->route_prefix . 'backend.ebook.index'),
                    trans('common.list'),
                    ['icon' => 'list', 'size' => 'sm', 'type' => 'success'],
                ],
                [
                    route($this->route_prefix . 'backend.ebook.edit', ['ebook' => $ebook->id]),
                    trans('common.edit'),
                    ['icon' => 'edit', 'size' => 'sm', 'type' => 'primary'],
                ],
                [
                    route($this->route_prefix . 'backend.ebook.preview', ['ebook' => $ebook->id]),
                    trans('common.preview'),
                    ['icon' => 'eye-open', 'size' => 'sm', 'type' => 'info', 'target' => '_blank'],
                ],
            ]
        );
        $ebook = $ebook->loadInfo();
        return view('ebook::backend.show', compact('ebook'));
    }

    /**
     * @param Ebook $ebook
     */
    public function preview(Ebook $ebook)
    {
        header("Content-type: {$ebook->filemime}");
        header('Content-Disposition: inline');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        readfile($ebook->filePath());
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function edit(Ebook $ebook)
    {
        $url = route($this->route_prefix . 'backend.ebook.update', ['ebook' => $ebook->id]);
        $method = 'put';
        $categories = $this->categoryManager->selectize();
        $name = trans('ebook::common.ebooks');
        $this->buildHeading(
            [trans('common.update'), $name],
            'edit',
            [route($this->route_prefix . 'backend.ebook.index') => $name, '#' => trans('common.edit')]
        );
        $ebook->enumRestore();
        $file_hint = trans('ebook::common.file_hint_edit');

        return view(
            'ebook::backend.form',
            compact('ebook', 'categories', 'url', 'method', 'categories', 'file_hint') + $ebook->loadEnums()
        );
    }

    /**
     * @param \Minhbang\Ebook\Request $request
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EbookRequest $request, Ebook $ebook)
    {
        $ebook->fill($request->all());
        $ebook->fillFeaturedImage($request);
        $ebook->fileFill($request);
        $ebook->enumDirty = true;
        $ebook->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.update_object_success', ['name' => trans('ebook::common.ebooks')]),
            ]
        );

        return redirect(route($this->route_prefix . 'backend.ebook.index'));
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Ebook $ebook)
    {
        $ebook->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('ebook::common.ebooks')]),
            ]
        );
    }

    /**
     * @param Ebook $ebook
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Ebook $ebook, $status)
    {
        $result = $ebook->statusUpdate($status) ? 'success' : 'error';

        return response()->json(['type' => $result, 'content' => trans("common.status_{$result}")]);
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'title' => [
                'rules' => [
                    'required|max:255',
                    'slug',
                    function ($title) {
                        return VnString::to_slug($title);
                    },
                    'required|max:255|alpha_dash',
                ],
                'label' => trans('ebook::common.title'),
            ],
        ];
    }
}
