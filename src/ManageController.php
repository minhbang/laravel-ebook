<?php
namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Ebook\Request as EbookRequest;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Session;
use Request;
use Datatable;
use Minhbang\Kit\Support\VnString;
use Response;

/**
 * Class Controller
 *
 * @package Minhbang\Ebook
 */
class ManageController extends BaseController
{
    use QuickUpdateActions;
    /**
     * @var \Minhbang\Category\Type
     */
    protected $categoryManager;
    /**
     * @var \Minhbang\Security\AccessControl
     */
    protected $accessControl;
    /**
     * @var int
     */
    protected $status;
    /**
     * Admin có thể set tất cả status (dropdown) hay chạy 'Qui trình' set status từng bước (button)
     *
     * @var bool
     */
    public $allStatus = true;
    /**
     * @var \Minhbang\Ebook\Html
     */
    protected $html;
    /**
     * @var \Minhbang\Ebook\Ebook
     */
    protected $model;
    /**
     * @var \Minhbang\Ebook\Datatable
     */
    protected $datatable;

    /**
     * @param int $status
     */
    protected function switchStatus($status = null)
    {
        $key = 'backend.ebook.status';
        $status = is_null($status) ? session($key, $this->accessControl->editingValue()) : $status;
        if ($this->accessControl->has($status)) {
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
        $this->model = $ebook;
        $this->categoryManager = $ebook->categoryManager();
        $this->accessControl = $ebook->accessControl();
        $this->datatable = $this->newClassInstance(config('ebook.datatable'), $this);
        parent::__construct();
        $this->switchStatus();
    }

    protected function initWeb()
    {
        parent::initWeb();
        $this->html = $this->newClassInstance(config('ebook.html'), $this->model);
        view()->share('html', $this->html);
    }

    /**
     * @param int $status
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function index($status = null)
    {
        $this->switchStatus($status);
        $this->datatable->share('backend');
        $name = trans('ebook::common.ebooks');
        $this->buildHeading(
            [trans('common.manage'), $name],
            'fa-file-pdf-o',
            ['#' => $name],
            [
                [route($this->route_prefix . 'backend.ebook.create'), trans('common.create'), ['type' => 'success', 'icon' => 'plus-sign']],
            ]
        );
        $current = $this->accessControl->get('title', $this->status);
        $statusTabs = $this->html->statusTabs($this->status, route($this->route_prefix . 'backend.ebook.index_status', ['status' => 'STATUS']));

        return view('ebook::backend.index', compact('current', 'statusTabs'));
    }


    /**
     * Danh sách Ebook theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\Ebook\Ebook $query */
        $query = Ebook::queryDefault()->status($this->status)->orderUpdated()->withEnumTitles();
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('ebooks.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('ebooks.updated_at', 'mb_date_vn2mysql');
        }

        return $this->datatable->make('backend', $query);
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
        $ebook->fillStatus($request->get('s'));
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
        $canUpdate = $ebook->allowed(user(), 'update');
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
                    trans('ilib::common.edit'),
                    ['icon' => 'edit', 'size' => 'sm', 'type' => 'primary', 'class' => $canUpdate ? null : 'disabled'],
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
        if ($ebook->allowed(user(), 'update')) {
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
            $ebook->fill($request->all() + ['featured' => 0]);
            $ebook->fillFeaturedImage($request);
            $ebook->fileFill($request);
            $ebook->user_id = user('id');
            $ebook->fillStatus($request->get('s'));
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

            return Response::json(
                [
                    'type'    => 'success',
                    'content' => trans('common.delete_object_success', ['name' => trans('ebook::common.ebooks')]),
                ]
            );
        } else {
            return Response::json(
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
        $result = $ebook->updateStatus($status) ? 'success' : 'error';

        return Response::json(['type' => $result, 'content' => trans("common.status_{$result}")]);
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
     *
     * @return bool
     */
    protected function quickUpdateAllowed($model)//, $attribute, $value)
    {
        return $model->allowed(user(), 'update');
    }
}
