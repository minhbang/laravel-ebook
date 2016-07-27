<?php
namespace Minhbang\Ebook;

use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\Status\Statusable;
use Minhbang\Status\StatusContract;
use Minhbang\Category\Categorized;
use Minhbang\Enum\EnumContract;
use Minhbang\Enum\HasEnum;
use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\FeaturedImage;
use Minhbang\Kit\Traits\Model\HasFile;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Minhbang\User\Support\UserQuery;
use DB;
use Minhbang\User\User;

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
 * @property integer $hit
 * @property boolean $featured
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Minhbang\Category\Category $category
 * @property-read \Minhbang\User\User $user
 * @property-read string $url
 * @property-read mixed $featured_image_url
 * @property-read mixed $featured_image_sm_url
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook forSelectize($take = 50)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook status($status)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
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
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook featured()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFilemime($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFilesize($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFeaturedImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePyear($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePages($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereLanguageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSecurityId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereWriterId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePublisherId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook wherePplaceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereSeriesId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereHit($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Ebook\Ebook published()
 */
class Ebook extends Model implements EnumContract, StatusContract
{
    /**
     * Mới tải lên: ebook do bạn đọc upload
     */
    const STATUS_UPLOADED = 0;
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
    use Statusable;
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
        'writer_id', 'publisher_id', 'pplace_id', 'series_id', 'featured',
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
     * Cập nhật thông tin khi Reader đọc toàn văn ebook này
     *
     * @return bool
     */
    public function updateRead()
    {
        /** @var User $user */
        $user = user();
        if (!$user->isSysSadmin() && !$user->hasRole('tv.*')) {
            DB::table('read_ebook')->insert([
                'reader_id' => $user->id,
                'ebook_id'  => $this->id,
                'read_at'   => Carbon::now(),
            ]);
        }

        $this->timestamps = false;
        $this->hit += 1;

        return $this->save();
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
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeFeatured($query)
    {
        return $query->where("{$this->table}.featured", 1);
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === static::STATUS_PUBLISHED;
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopePublished($query)
    {
        return $query->where("{$this->table}.status", static::STATUS_PUBLISHED);
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
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->id ? route('ilib.ebook.detail', ['ebook' => $this->id]) : null;
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
     * User hiện tại có thể DELETE ebook này không?
     *
     * @return bool
     */
    public function canDelete()
    {
        return user()->hasRole('tv.pt') || $this->status != static::STATUS_PUBLISHED;
    }

    /**
     * User hiện tại có thể UPDATE ebook này không?
     *
     * @return bool
     */
    public function canUpdate()
    {
        return $this->canDelete();
    }

    /**
     * All statuses
     *
     * @return array
     */
    public function statuses()
    {
        return [
            static::STATUS_UPLOADED   => trans('ebook::common.status_uploaded'),
            static::STATUS_PROCESSING => trans('ebook::common.status_processing'),
            static::STATUS_PENDING    => trans('ebook::common.status_pending'),
            static::STATUS_PUBLISHED  => trans('ebook::common.status_published'),
        ];
    }

    /**
     * All actions
     *
     * @return array
     */
    public function statusActions()
    {
        return [
            static::STATUS_UPLOADED   => trans('ebook::common.status_action_uploaded'),
            static::STATUS_PROCESSING => trans('ebook::common.status_action_processing'),
            static::STATUS_PENDING    => trans('ebook::common.status_action_pending'),
            static::STATUS_PUBLISHED  => trans('ebook::common.status_action_published'),
        ];
    }

    /**
     * Từ status này có thể chuyển sang
     *
     * @param null|int $status
     *
     * @return array
     */
    public function statusCanUpdate($status = null)
    {
        // Là phụ trách thư viện
        $isPT = user()->hasRole('tv.pt');

        $map = [
            static::STATUS_UPLOADED   => [static::STATUS_PROCESSING],
            static::STATUS_PROCESSING => $isPT ? [static::STATUS_PUBLISHED] : [static::STATUS_PENDING],
            static::STATUS_PENDING    => $isPT ? [static::STATUS_PROCESSING, static::STATUS_PUBLISHED] : [static::STATUS_PROCESSING],
            static::STATUS_PUBLISHED  => $isPT ? [static::STATUS_PROCESSING] : [],
        ];
        $can = $map[$this->status];

        return is_null($status) ? $can : in_array($status, $can);
    }


    /**
     * All statuses
     *
     * @return array
     */
    public function statusCss()
    {
        return [
            static::STATUS_UPLOADED   => 'warning',
            static::STATUS_PROCESSING => 'danger',
            static::STATUS_PENDING    => 'info',
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
