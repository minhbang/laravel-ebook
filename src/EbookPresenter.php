<?php
namespace Minhbang\Ebook;

use Minhbang\Enum\EnumPresenter;
use Html;
use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
use Minhbang\Status\StatusPresenter;

/**
 * Class EbookPresenter
 *
 * @property-read \Minhbang\Ebook\Ebook $entity
 * @package Minhbang\Ebook\Presenters
 */
class EbookPresenter extends EnumPresenter
{
    use DatetimePresenter;
    use StatusPresenter;

    public function fileicon()
    {
        /** @var \Minhbang\File\File $file */
        if ($file = $this->entity->files->first()) {
            return $file->present()->icon;
        } else {
            return null;
        }
    }

    /**
     * @param string $except
     *
     * @return string
     */
    public function securityFormated($except = null)
    {
        $css = $this->entity->security_params;

        return $css && $except && ($css === $except) ? '' : "<span class=\"label label-{$css}\">{$this->entity->security}</span>";
    }

    /**
     * @param array $options
     *
     * @return null|string
     */
    public function categories(
        $options = [
            'before'    => '<span class="label label-primary">',
            'after'     => '</span>',
            'separator' => '</span><br><span class="label label-primary">',
        ]
    ) {
        $categories = array_map(function ($category) {
            /** @var \Minhbang\Category\Category $category */
            return $category->title;
        }, $this->entity->categories->all());

        return Html::implode($categories, $options);
    }

    /**
     * @return string
     */
    public function summary()
    {
        return str_limit($this->entity->summary, setting('display.summary_limit'));
    }

    /**
     * @param string|null $class
     * @param bool $sm
     * @param bool $title
     * @param string $size
     *
     * @return string
     */
    public function featured_image($class = 'img-responsive', $sm = false, $title = false, $size = '_md')
    {
        if ($src = $this->entity->featuredImageUrl($sm)) {
            $class  = $class ? " class =\"$class\"" : '';
            $html   = $title ? "<div class=\"title\">{$this->entity->title}</div>" : '';
            $width  = $this->entity->config['featured_image']["width{$size}"];
            $height = $this->entity->config['featured_image']["height{$size}"];

            return "<img{$class} src=\"$src\" title=\"{$this->entity->title}\" width=\"$width\" height=\"$height\"/>{$html}";
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function featured_image_lightbox()
    {
        $img = $this->featured_image('', true, false, '_sm');

        return "<a href=\"{$this->entity->featured_image_url}\" data-lightbox=\"ebook-{$this->entity->id}\">{$img}</a>";
    }

    /**
     * @param string $route
     * @param array $params
     * @param array $options
     *
     * @return string
     */
    public function files($route = 'backend.file.preview', $params = [], $options = [])
    {
        $name   = mb_array_extract('name', $options, 'title');
        $result = [];
        if ($files = $this->entity->files) {
            foreach ($files as $file) {
                $result[] = $file->present()->link($route, $params, $name);
            }
        }

        return Html::implode($result, $options);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function file_titles($options = [])
    {
        $name   = mb_array_extract('name', $options, 'title');
        $result = [];
        if ($files = $this->entity->files) {
            foreach ($files as $file) {
                $result[] = $file->present()->title($name);
            }
        }

        return Html::implode($result, $options);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function form_files($name = 'title')
    {
        $result = '';
        if ($files = $this->entity->files) {
            foreach ($files as $file) {
                $result .= <<<"ITEM"
<div class="form-group">
    <div class="input-group">
        <div class="form-control">{$file->present()->title($name)}</div>
        <span class="input-group-addon">
            <i class="fa fa-remove text-danger remove"></i>
            <i class="fa fa-reorder reorder"></i>
        </span>
    </div>
</div>
ITEM;
            }
        }
        $label   = trans('ebook::common.files');
        $add_new = trans('file::common.add_new');

        return <<<"RESULT"
<div class="form-files">        
    <div class="control-label">
        $label
        <a href="#" class="btn btn-xs btn-primary add-new"><i class="fa fa-plus"></i> $add_new</a>
    </div>
    <div class="files">
        $result
    </div>
</div>
RESULT;
    }

    /**
     * @return string
     */
    public function title_block()
    {
        /** @var \Minhbang\Ebook\Ebook $model */
        $model = $this->entity;

        $title = $model->canUpdate() ? Html::linkQuickUpdate(
            $model->id,
            $model->title,
            [
                'attr'      => 'title',
                'title'     => trans("ebook::common.title"),
                'class'     => 'w-lg',
                'placement' => 'top',
            ]
        ) : $model->title;
        $info  = '';
        if ($model->status !== Ebook::STATUS_UPLOADED) {
            $info .= "— <small class='text-muted'>{$model->writer}, " . trans('ebook::common.publisher_id_th') . ": {$model->publisher}</small><br>";
        }
        $info .= "— <small class='text-muted'>{$this->createdAt()}</small>";

        return "<div class=\"title\">{$title}</div><div class=\"info\">{$info}</div>";
    }

    /**
     * @return string
     */
    public function title_block_1()
    {
        /** @var \Minhbang\Ebook\Ebook $model */
        $model = $this->entity;
        $title = '<a href="' . route('ilib.backend.ebook.show',
                ['ebook' => $model->id]) . '">' . $model->title . '</a>';
        $info  = "<small class='text-muted'>{$model->writer}, " . trans('ebook::common.publisher_id_th') . ": {$model->publisher}</small><br>";
        $info .= "<small class='text-muted'>{$this->createdAt()}</small>";

        return "<div class=\"title\">{$title}</div><div class=\"info\">{$info}</div>";
    }
}