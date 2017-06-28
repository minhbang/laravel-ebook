<?php namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Ebook\Request as EbookRequest;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Session;
use Request;
use Minhbang\Kit\Support\VnString;
use Minhbang\File\File;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Datatables;
use CategoryManager;
use Status;

/**
 * Class BackendController
 *
 * @package Minhbang\Ebook
 */
class BackendController extends BaseController {
    use QuickUpdateActions;

    /**
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     * @param string $status
     *
     * @return \Illuminate\View\View
     */
    public function index( Builder $builder, $status = null ) {
        $name = trans( 'ebook::common.ebooks' );
        if ( $status ) {
            $status_title = Status::of( Ebook::class )->get( $status, 'title' );
            $this->buildHeading(
                [ trans( 'common.manage' ), $name . '<span class="text-info"> [' . $status_title . ']</span>' ],
                'fa-file-pdf-o',
                [ route( $this->route_prefix . 'backend.ebook.index' ) => $name, '#' => $status_title ]
            );
        } else {
            $this->buildHeading( [ trans( 'common.manage' ), $name ], 'fa-file-pdf-o', [ '#' => $name ] );
        }
        $builder->ajax( route( $this->route_prefix . 'backend.ebook.data', [ 'status' => $status ] ) );
        $html = $builder->columns( [
            [ 'data' => 'id', 'name' => 'id', 'title' => 'ID', 'class' => 'min-width text-right' ],
            [
                'data'       => 'featured_image',
                'name'       => 'featured_image',
                'title'      => trans( 'ebook::common.featured_image' ),
                'class'      => 'min-width',
                'orderable'  => false,
                'searchable' => false,
            ],
            [ 'data' => 'title', 'name' => 'title', 'title' => trans( 'ebook::common.ebook' ) ],
            [
                'data'       => 'files',
                'name'       => 'files',
                'title'      => trans( 'ebook::common.files' ),
                'orderable'  => false,
                'searchable' => false,
            ],
            [
                'data'       => 'status',
                'name'       => 'status',
                'title'      => trans( 'ebook::common.status' ),
                'orderable'  => false,
                'searchable' => false,
                'class'      => 'min-width',
            ],
        ] )->addAction( [
            'data'  => 'actions',
            'name'  => 'actions',
            'title' => trans( 'common.actions' ),
            'class' => 'min-width',
        ] );

        return view( 'ebook::backend.index', compact( 'html' ) );
    }


    /**
     * Danh sách Ebook theo định dạng của Datatables.
     *
     * @param string $status
     *
     * @return
     */
    public function data( $status = null ) {
        $query = Ebook::queryDefault()->status( $status )->withEnumTitles();
        if ( Request::has( 'search_form' ) ) {
            $query = $query
                ->searchWhereBetween( 'ebooks.created_at', 'mb_date_vn2mysql' )
                ->searchWhereBetween( 'ebooks.updated_at', 'mb_date_vn2mysql' );
        }

        return Datatables::of( $query )->setTransformer( new EbookTransformer( $this->route_prefix . 'backend' ) )->make( true );
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create() {
        $ebook = new Ebook();
        $url = route( $this->route_prefix . 'backend.ebook.store' );
        $method = 'post';
        $categories = CategoryManager::of( Ebook::class )->selectize();
        $this->buildHeading(
            [ trans( 'common.create' ), trans( 'ebook::common.ebooks' ) ],
            'plus-sign',
            [
                route( $this->route_prefix . 'backend.ebook.index' ) => trans( 'ebook::common.ebook' ),
                '#'                                                  => trans( 'common.create' ),
            ]
        );
        $selectize_statuses = $this->getSelectizeStatuses();
        $files = [];

        return view(
            'ebook::backend.form',
            compact( 'ebook', 'url', 'method', 'categories', 'files', 'selectize_statuses' ) + $ebook->loadEnums()
        );
    }

    /**
     * @param \Minhbang\Ebook\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store( EbookRequest $request ) {
        $ebook = new Ebook();
        $ebook->fill( $request->all() );
        $ebook->fillFeaturedImage( $request );
        $ebook->user_id = user( 'id' );
        $ebook->save();
        $ebook->fillFiles( $request->get( 'selectedFiles' ) );
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans( 'common.create_object_success', [ 'name' => trans( 'ebook::common.ebooks' ) ] ),
            ]
        );

        return redirect( route( $this->route_prefix . 'backend.ebook.index' ) );
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function show( Ebook $ebook ) {
        $name = trans( 'ebook::common.ebooks' );
        $canUpdate = $ebook->isReady( 'update' );
        $this->buildHeading(
            [ trans( 'common.view_detail' ), $name ],
            'list',
            [ route( $this->route_prefix . 'backend.ebook.index' ) => $name, '#' => trans( 'common.view_detail' ) ],
            [
                [
                    route( $this->route_prefix . 'backend.ebook.index' ),
                    trans( 'common.list' ),
                    [ 'icon' => 'list', 'size' => 'sm', 'type' => 'success' ],
                ],
                [
                    $canUpdate ? route( $this->route_prefix . 'backend.ebook.edit', [ 'ebook' => $ebook->id ] ) : '#',
                    trans( 'common.edit' ),
                    [
                        'icon'  => 'edit',
                        'size'  => 'sm',
                        'type'  => 'primary',
                        'class' => $canUpdate ? null : 'disabled',
                    ],
                ],
            ]
        );
        $ebook = $ebook->loadInfo();

        return view( 'ebook::backend.show', compact( 'ebook' ) );
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function edit( Ebook $ebook ) {
        if ( $ebook->isReady( 'update' ) ) {
            $url = route( $this->route_prefix . 'backend.ebook.update', [ 'ebook' => $ebook->id ] );
            $method = 'put';
            $categories = CategoryManager::of( Ebook::class )->selectize();
            $selectize_statuses = $this->getSelectizeStatuses();
            $name = trans( 'ebook::common.ebooks' );
            $this->buildHeading(
                [ trans( 'common.update' ), $name ],
                'edit',
                [ route( $this->route_prefix . 'backend.ebook.index' ) => $name, '#' => trans( 'common.edit' ) ]
            );
            $files = $ebook->filesForReturn();

            return view(
                'ebook::backend.form',
                compact( 'ebook', 'categories', 'url', 'method', 'categories', 'selectize_statuses', 'files' ) + $ebook->loadEnums()
            );
        } else {
            return view( 'message', [
                'module'  => trans( 'ilib::common.ilib' ),
                'type'    => 'danger',
                'content' => trans( 'ilib::common.messages.unable_update' ),
            ] );
        }
    }

    /**
     * @param \Minhbang\Ebook\Request $request
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update( EbookRequest $request, Ebook $ebook ) {
        if ( $ebook->isReady( 'update' ) ) {
            $ebook->fill( $request->all() + [ 'featured' => 0 ] );
            $ebook->fillFeaturedImage( $request );
            $ebook->user_id = user( 'id' );
            $ebook->save();
            $ebook->fillFiles( $request->get( 'selectedFiles' ) );
            Session::flash(
                'message',
                [
                    'type'    => 'success',
                    'content' => trans( 'common.update_object_success', [ 'name' => trans( 'ebook::common.ebooks' ) ] ),
                ]
            );
        } else {
            Session::flash(
                'message',
                [
                    'type'    => 'danger',
                    'content' => trans( 'ilib::common.messages.unable_update' ),
                ]
            );
        }

        return redirect( route( $this->route_prefix . 'backend.ebook.index' ) );
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Ebook $ebook ) {
        return $ebook->isReady( 'update' ) && $ebook->delete() ?
            response()->json(
                [
                    'type'    => 'success',
                    'content' => trans( 'common.delete_object_success', [ 'name' => trans( 'ebook::common.ebooks' ) ] ),
                ]
            ) : response()->json(
                [
                    'type'    => 'danger',
                    'content' => trans( 'ilib::common.messages.unable_delete' ),
                ]
            );
    }

    /**
     * Chỉ Phụ trách Thư viện mới có quyền Quick Update Status
     *
     * @param Ebook $ebook
     * @param string $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status( Ebook $ebook, $status ) {
        $result = user_is( 'thu_vien.phu_trach' ) ? 'success' : 'error';
        if ( $result == 'success' ) {
            $ebook->update( [ 'status' => $status ] );
        }

        return response()->json( [ 'type' => $result, 'content' => trans( "common.status_{$result}" ) ] );
    }

    /**
     * Xem trước file
     *
     * @param \Minhbang\File\File $file
     */
    public function preview( File $file ) {
        $file->response();
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes() {
        return [
            'title' => [
                'rules' => [
                    'required|max:255',
                    'slug',
                    function ( $title ) {
                        return VnString::to_slug( $title );
                    },
                    'required|max:255|alpha_dash',
                ],
                'label' => trans( 'ebook::common.title' ),
            ],
        ];
    }

    /**
     * @param Ebook $model
     *
     * @return bool
     */
    protected function quickUpdateAllowed( $model )//, $attribute, $value)
    {
        return $model->isReady( 'update' );
    }

    /**
     * @return array
     */
    protected function getSelectizeStatuses() {
        return Status::of( Ebook::class )->groupByLevel();
    }
}
