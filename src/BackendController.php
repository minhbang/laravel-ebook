<?php
namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Ebook\Request as EbookRequest;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Session;
use Request;
use Datatable;
use Minhbang\Kit\Support\VnString;
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
     * @var int
     */
    protected $status;

    /**
     * Danh sách Statuses
     *
     * @var array
     */
    protected $statuses;

    protected $allStatus = true;

    /**
     * @param null|string $status
     */
    protected function switchStatus($status = null)
    {
        $key = 'backend.ebook.status';
        $status = is_null($status) ? session($key, key($this->statuses)) : $status;
        if (isset($this->statuses[(int)$status])) {
            $this->status = $status;
            session([$key => $status]);
        } else {
            Session::forget($key);
            abort(404, trans('common.status_invalid'));
        }
    }

    /**
     * Controller constructor.
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     */
    public function __construct(Ebook $ebook)
    {
        parent::__construct();
        $this->categoryManager = app('category-manager')->root('ebook');
        $this->statuses = $ebook->statuses();
        $this->switchStatus();
    }

    /**
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param int $status
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function index(Ebook $ebook, $status = null)
    {
        $this->switchStatus((int)$status);
        $isReaderUploaded = $this->status === Ebook::STATUS_UPLOADED;
        $tableOptions = [
            'id'        => 'ebook-manage',
            'class'     => 'table-ebooks',
            'row_index' => true,
        ];

        $options = $isReaderUploaded ?
            [
                'aoColumnDefs' => [
                    ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                    ['sClass' => 'min-width text-right', 'aTargets' => [-1]],
                ],
            ] :
            [
                'aoColumnDefs' => [
                    ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                    ['sClass' => 'min-width', 'aTargets' => [1]],
                    ['sClass' => 'min-width text-right', 'aTargets' => [-1, -2]],
                ],
            ];
        $table = Datatable::table()->setOptions($options)->setCustomValues($tableOptions);
        if ($isReaderUploaded) {
            $table->addColumn(
                '',
                trans('ebook::common.ebook'),
                trans('common.actions')
            );
        } else {
            $table->addColumn(
                '',
                trans('ebook::common.featured_image'),
                trans('ebook::common.ebook'),
                trans('ebook::common.security_id'),
                trans('common.actions')
            );
        }
        $name = trans('ebook::common.ebooks');
        $this->buildHeading(
            [trans('common.manage'), $name],
            'fa-file-pdf-o',
            ['#' => $name],
            $ebook->present()->buttons($this->status, route($this->route_prefix . 'backend.ebook.index_status', ['status' => 'STATUS']))
        );
        $current = (new Ebook())->statusTitles($this->status);
        return view('ebook::backend.index', compact('tableOptions', 'options', 'table', 'current'));
    }


    /**
     * Danh sách Ebook theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        $isReaderUploaded = $this->status === Ebook::STATUS_UPLOADED;
        /** @var \Minhbang\Ebook\Ebook $query */
        $query = Ebook::queryDefault()->status($this->status)->orderUpdated();
        if (!$isReaderUploaded) {
            $query->withEnumTitles();
        }
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('ebooks.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('ebooks.updated_at', 'mb_date_vn2mysql');
        }
        $datatable = Datatable::query($query)
            ->addColumn(
                'index',
                function (Ebook $model) {
                    return $model->id;
                }
            );
        if ($isReaderUploaded) {
            $datatable = $datatable
                ->addColumn(
                    'index',
                    function (Ebook $model) {
                        return $model->id;
                    }
                )
                ->addColumn(
                    'title',
                    function (Ebook $model) {
                        return $model->present()->title_block;
                    }
                )
                ->addColumn(
                    'actions',
                    function (Ebook $model) {
                        $edit = Html::linkButton(
                            route($this->route_prefix . 'backend.ebook.edit', ['ebook' => $model->id]),
                            trans('ebook::common.status_action_processing'),
                            ['size' => 'xs', 'type' => 'danger']
                        );

                        return $edit . Html::tableActions(
                            $this->route_prefix . 'backend.ebook',
                            ['ebook' => $model->id],
                            $model->title,
                            trans('ebook::common.ebook'),
                            [
                                'renderPreview' => 'link',
                                'renderEdit'    => false,
                                'renderDelete'  => 'link',
                                'renderShow'    => 'link',
                            ]
                        );
                    }
                );
        } else {
            $datatable = $datatable
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
                    'actions',
                    function (Ebook $model) {
                        $url = route($this->route_prefix . 'backend.ebook.status', ['ebook' => $model->id, 'status' => 'STATUS']);
                        $statuses = $this->allStatus ?
                            $model->present()->status($url) . '<br>' : $model->present()->statusActions($url);

                        return $statuses . Html::tableActions(
                            $this->route_prefix . 'backend.ebook',
                            ['ebook' => $model->id],
                            $model->title,
                            trans('ebook::common.ebook'),
                            [
                                'renderPreview' => 'link',
                                'renderEdit'    => $model->canUpdate() ? 'link' : 'disabled',
                                'renderDelete'  => $model->canDelete() ? 'link' : 'disabled',
                                'renderShow'    => 'link',
                            ]
                        );
                    }
                );
        }

        return $datatable->searchColumns('ebooks.title')->make();
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function create(Ebook $ebook)
    {
        $url = route($this->route_prefix . 'backend.ebook.store');
        $method = 'post';
        $categories = $this->categoryManager->selectize();
        $this->buildHeading(
            [trans('common.create'), trans('ebook::common.ebooks')],
            'plus-sign',
            [
                route($this->route_prefix . 'backend.ebook.index') => trans('ebook::common.ebook'),
                '#'                                                => trans('common.create'),
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
        $ebook->status = Ebook::STATUS_PROCESSING;
        if (($status = $request->get('s')) && $ebook->statusCanUpdate($status)) {
            $ebook->status = $status;
        }

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
        $canUpdate = $ebook->canUpdate();
        $isReaderUploaded = $ebook->status === Ebook::STATUS_UPLOADED;
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
                    $canUpdate ? route($this->route_prefix . 'backend.ebook.edit', ['ebook' => $ebook->id]) : '#',
                    $isReaderUploaded ? trans('ilib::common.edit') : trans('common.edit'),
                    ['icon' => 'edit', 'size' => 'sm', 'type' => 'primary', 'class' => $canUpdate ? null : 'disabled'],
                ],
                [
                    route($this->route_prefix . 'backend.ebook.preview', ['ebook' => $ebook->id]),
                    trans('common.preview'),
                    ['icon' => 'eye-open', 'size' => 'sm', 'type' => 'info', 'target' => '_blank'],
                ],
            ]
        );
        if ($isReaderUploaded) {
            return view('ebook::backend.show_uploaded', compact('ebook'));
        } else {
            $ebook = $ebook->loadInfo();

            return view('ebook::backend.show', compact('ebook'));
        }
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
        if ($ebook->canUpdate()) {
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
        } else {
            return view('message', [
                'module'  => trans('ilib::common.ilib'),
                'type'    => 'danger',
                'content' => trans('ilib::common.messages.unable_update'),
            ]);
        }
    }

    /**
     * @param \Minhbang\Ebook\Request $request
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EbookRequest $request, Ebook $ebook)
    {
        if ($ebook->canUpdate()) {
            $isReaderUploaded = $ebook->status === Ebook::STATUS_UPLOADED;
            $ebook->fill($request->all() + ['featured' => 0]);
            $ebook->fillFeaturedImage($request);
            $ebook->fileFill($request);
            if ($isReaderUploaded) {
                $ebook->user_id = user('id');
            }
            if (($status = $request->get('s')) && $ebook->statusCanUpdate($status)) {
                $ebook->status = $status;
            } else {
                if ($isReaderUploaded) {
                    $ebook->status = Ebook::STATUS_PROCESSING;
                }
            }

            $ebook->enumDirty = true;
            $ebook->save();
            Session::flash(
                'message',
                [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('ebook::common.ebooks')]),
                ]
            );
        } else {
            Session::flash(
                'message',
                [
                    'type'    => 'danger',
                    'content' => trans('ilib::common.messages.unable_update'),
                ]
            );
        }

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
        if ($ebook->canDelete()) {
            $ebook->delete();

            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans('common.delete_object_success', ['name' => trans('ebook::common.ebooks')]),
                ]
            );
        } else {
            return response()->json(
                [
                    'type'    => 'danger',
                    'content' => trans('ilib::common.messages.unable_delete'),
                ]
            );
        }
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

    /**
     * @param Ebook $model
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    protected function quickUpdateAllowed($model, $attribute, $value)
    {
        return $model->canUpdate();
    }
}
