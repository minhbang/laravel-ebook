<?php namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\ModelTransformer;
use Html;

/**
 * Class EbookTransformer
 */
class EbookTransformer extends ModelTransformer {
    protected $isPTTV = false;

    public function __construct( $zone = 'backend' ) {
        parent::__construct( $zone );
        $this->isPTTV = user_is( 'thu_vien.phu_trach' );
    }

    /**
     * TODO chưa sử dụng các nút chuyển status Up, Down status
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return array
     */
    public function transform( Ebook $ebook ) {
        return [
            'id'             => (int) $ebook->id,
            'featured_image' => $ebook->present()->featured_image_lightbox,
            'title'          => $ebook->present()->title_block,
            'files'          => $ebook->present()->files( "{$this->zone}.ebook.preview" ),
            'updated_at'     => $ebook->present()->updatedAt(['template' => ':time, :date']),
            'status'         => $this->isPTTV ?
                $ebook->present()->statusQuickUpdate( route( "{$this->zone}.ebook.status", [ 'ebook' => $ebook->id, 'status' => 'STATUS' ] ) ) :
                $ebook->present()->status,
            'actions'        => Html::tableActions(
                "{$this->zone}.ebook",
                [ 'ebook' => $ebook->id ],
                $ebook->title,
                trans( 'ebook::common.ebook' ),
                [
                    'renderEdit'   => $ebook->isReady( 'update' ) ? 'link' : 'disabled',
                    'renderDelete' => $ebook->isReady( 'delete' ) ? 'link' : 'disabled',
                    'renderShow'   => 'link',
                ]
            ),
        ];
    }
}