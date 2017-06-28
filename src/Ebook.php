<?php namespace Minhbang\Ebook;

use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\Category\Categorized;
use Minhbang\Enum\UseEnum;
use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\FeaturedImage;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Minhbang\Status\Traits\Statusable;
use Minhbang\User\Support\HasOwner;
use Minhbang\File\Support\Fileable;
use DB;

/**
 * Class Ebook
 *
 * @package Minhbang\Ebook
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\File\File[] $files
 * @property-read string $status_title
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook published()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook status( $status )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withEnumTitles()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook ready( $action, $by = null )
 * Enums ---
 * @property-read string $language_title
 * @property-read string $security_title
 * @property-read string $writer_title
 * @property-read string $publisher_title
 * @property-read string $pplace_title
 * ---
 * @property-read string $language_params
 * @property-read string $security_params
 * @property-read string $writer_params
 * @property-read string $publisher_params
 * @property-read string $pplace_params
 * ------------------------------------------------------------------------------------------------------------
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $featured_image
 * @property int $pyear
 * @property int $pages
 * @property int $category_id
 * @property int $language_id
 * @property int $security_id
 * @property int $writer_id
 * @property int $publisher_id
 * @property int $pplace_id
 * @property int $series_id
 * @property int $user_id
 * @property string $status
 * @property int $hit
 * @property bool $featured
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Minhbang\Category\Category $category
 * @property-read string $featured_image_sm_url
 * @property-read string $featured_image_url
 * @property-read string $url
 * @property-read \Minhbang\User\User $user
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook categorized( $category = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except( $ids )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook featured()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText( $column, $text )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook forSelectize( $take = 50 )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook mine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook notMine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook orderCreated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook orderUpdated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook period( $start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchKeyword( $keyword, $columns = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhere( $column, $operator = '=', $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereBetween( $column, $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereIn( $column, $fn )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereInDependent( $column, $column_dependent, $fn, $empty = [] )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook thisMonth( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook thisWeek( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook today( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes( $attributes )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereCategoryId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereCreatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFeatured( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFeaturedImage( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereHit( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereLanguageId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePages( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePplaceId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePublisherId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePyear( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSecurityId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSeriesId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSlug( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereStatus( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSummary( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereTitle( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereUserId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereWriterId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withAuthor( $attribute = 'username' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withCategoryTitle()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook yesterday( $same_time = false, $field = 'created_at' )
 */
class Ebook extends Model {
    use SearchQuery;
    use Categorized;
    use HasOwner;
    use PresentableTrait;
    use FeaturedImage;
    use DatetimeQuery;
    use UseEnum;
    use Fileable;
    use Statusable;

    protected $table = 'ebooks';
    protected $presenter = Presenter::class;
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'pyear',
        'pages',
        'category_id',
        'language_id',
        'security_id',
        'writer_id',
        'publisher_id',
        'pplace_id',
        'series_id',
        'featured',
        'status',
    ];
    /**
     * Các columns có thể search
     * Khi search các enums, CHÚ Ý alias của table, vd
     * language_id ===> languages.title
     * security_id ===> securities.title
     *
     * @var array
     */
    protected $searchable = [ 'title' ];

    /**
     * Các thuộc tính enums được bảo vệ, chỉ chọn, không cho phép tạo mới
     *
     * @var array
     */
    protected $enumGuarded = [ 'security_id' ];

    /**
     * Ebook constructor.
     *
     * @param array $attributes
     */
    public function __construct( array $attributes = [] ) {
        parent::__construct( $attributes );
        $this->config( [
            'featured_image' => config( 'ebook.featured_image' ),
        ] );
    }

    /**
     * Cập nhật thông tin khi Reader đọc toàn văn ebook này
     *
     * @return bool
     */
    public function updateRead() {
        if ( ! authority()->user()->isAdmin() && ! authority()->user()->hasRole( 'thu_vien.*' ) ) {
            DB::table( 'read_ebook' )->insert( [
                'reader_id' => user( 'id' ),
                'ebook_id'  => $this->id,
                'read_at'   => Carbon::now(),
            ] );
        }

        $this->timestamps = false;
        $this->hit += 1;

        return $this->save();
    }

    /**
     * @return static
     */
    public function loadInfo() {
        return static::where( 'ebooks.id', $this->id )->queryDefault()->withEnumTitles()->withCategoryTitle()->first();
    }

    /**
     * Danh sách ebook có liên quan
     *
     * @param int $limit
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function related( $limit = 9 ) {
        return static::queryDefault()->except()->withEnumTitles()
                     ->categorized( $this->category )->orderUpdated()->take( $limit );
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault( $query ) {
        return $query->select( "{$this->table}.*" );
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeFeatured( $query ) {
        return $query->where( "{$this->table}.featured", 1 );
    }

    /**
     * Lấy $take ebooks phục vụ selectize ebooks
     *
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeForSelectize( $query, $take = 50 ) {
        return $query->select( [ 'id', 'title' ] )->take( $take );
    }

    /**
     * getter $ebook->url
     *
     * @return string
     */
    public function getUrlAttribute() {
        return $this->id ? route( 'ilib.ebook.detail', [ 'ebook' => $this->id ] ) : null;
    }
}
