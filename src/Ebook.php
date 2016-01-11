<?php
namespace Minhbang\Ebook;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\AccessControl\Contracts\ResourceStatus;
use Minhbang\AccessControl\Traits\Resource\HasStatus;
use Minhbang\Category\Categorized;
use Minhbang\Enum\EnumContract;
use Minhbang\Enum\HasEnum;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\FeaturedImage;
use Minhbang\LaravelKit\Traits\Model\HasFile;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Minhbang\LaravelUser\Support\UserQuery;

/**
 * Class Ebook
 *
 * @package Minhbang\Ebook
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $filename
 * @property string $filemime
 * @property integer $filesize
 * @property string $summary
 * @property string $featured_image
 * @property integer $pyear
 * @property integer $pages
 * @property integer $category_id
 * @property integer $language_id
 * @property integer $security_id
 * @property integer $writer_id
 * @property integer $publisher_id
 * @property integer $pplace_id
 * @property integer $series_id
 * @property integer $user_id
 * @property integer $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Minhbang\Category\Item $category
 * @property-read \Minhbang\LaravelUser\User $user
 * @property-read mixed $featured_image_url
 * @property-read mixed $featured_image_sm_url
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook forSelectize($take = 50)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook status($status)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereInDependent($column, $column_dependent, $fn, $empty = array())
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook categorized($category = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withCategoryTitle()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook notMine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook mine()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withAuthor()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook withEnumTitles()
 */
class Ebook extends Model implements ResourceStatus, EnumContract
{
    /**
     * Đang biên mục: chỉ nhân viên thư viện được phép xem
     * Mặc định
     */
    const STATUS_PROCESSING = 1;
    /**
     * Chờ duyệt: chỉ nhân viên và phụ trách thư viện được xem
     */
    const STATUS_PENDING = 2;
    /**
     * Đã xuất bản: được phép xem
     */
    const STATUS_PUBLISHED = 3;

    use SearchQuery;
    use HasStatus;
    use Categorized;
    use UserQuery;
    use PresentableTrait;
    use FeaturedImage;
    use DatetimeQuery;
    use HasEnum;
    use HasFile;

    protected $table = 'ebooks';
    protected $presenter = Presenter::class;
    protected $fillable = [
        'title', 'slug', 'summary', 'pyear', 'pages', 'category_id', 'language_id', 'security_id',
        'writer_id', 'publisher_id', 'pplace_id', 'series_id',
    ];
    /**
     * Các columns có thể search
     * Khi search các enums, CHÚ Ý alias của table, vn
     * language_id ===> languages.title
     * security_id ===> securities.title
     *
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * Ebook constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->config([
            'featured_image' => config('ebook.featured_image'),
        ]);
    }

    /**
     * @return static
     */
    public function loadInfo()
    {
        return static::where('ebooks.id', $this->id)->queryDefault()->withEnumTitles()->withCategoryTitle()->first();
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function related($limit = 9)
    {
        return static::queryDefault()->except()->withEnumTitles()->categorized($this->category)->orderUpdated()->take($limit);
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault($query)
    {
        return $query->select("{$this->table}.*");
    }

    /**
     * Lấy $take ebooks phục vụ selectize ebooks
     *
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeForSelectize($query, $take = 50)
    {
        return $query->select(['id', 'title'])->take($take);
    }

    /**
     * Hook các events của model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        // trước khi xóa $model, sẽ xóa hình bìa của nó
        static::deleting(
            function ($model) {
                /** @var static $model */
                $model->deleteFeaturedImage();
            }
        );
    }

    /**
     * All statuses
     *
     * @return array
     */
    public function statuses()
    {
        return [
            static::STATUS_PROCESSING => trans('ebook::common.status_processing'),
            static::STATUS_PENDING    => trans('ebook::common.status_pending'),
            static::STATUS_PUBLISHED  => trans('ebook::common.status_published'),
        ];
    }

    /**
     * All statuses
     *
     * @return array
     */
    public function statusCss()
    {
        return [
            static::STATUS_PROCESSING => 'default',
            static::STATUS_PENDING    => 'danger',
            static::STATUS_PUBLISHED  => 'primary',
        ];
    }

    /**
     * @return string
     */
    public function enumGroup()
    {
        return 'ebook';
    }

    /**
     * @return string
     */
    public function enumGroupTitle()
    {
        return trans('ebook::common.ebook');
    }

    /**
     * Các attributes có giá trị là các Enum
     *
     * @return string
     */
    protected function enumAttributes()
    {
        return [
            'language_id'  => trans('ebook::common.language_id'),
            'security_id'  => trans('ebook::common.security_id'),
            'writer_id'    => trans('ebook::common.writer_id'),
            'publisher_id' => trans('ebook::common.publisher_id'),
            'pplace_id'    => trans('ebook::common.pplace_id'),
        ];
    }

    /**
     * @return array
     */
    protected function enumGuarded()
    {
        return ['security_id'];
    }

    /**
     * Cấu hình cho trait HasFile
     *
     * @return array
     */
    protected function fileConfig()
    {
        return [
            'name' => 'filename',
            'mime' => 'filemime',
            'size' => 'filesize',
            'dir'  => storage_path('data/' . config('ebook.data_dir')),
        ];
    }
}
