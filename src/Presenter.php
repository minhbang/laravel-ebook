<?php
namespace Minhbang\Ebook;

use Minhbang\Enum\EnumPresenter;
use Form;
use Html;
use Minhbang\LaravelKit\Traits\Presenter\DatetimePresenter;
use Minhbang\LaravelKit\Traits\Presenter\FilePresenter;

/**
 * Class Presenter
 *
 * @package Minhbang\Ebook
 */
class Presenter extends EnumPresenter
{
    use DatetimePresenter;
    use FilePresenter;

    /**
     * @return string
     */
    public function securityFormated($except = null)
    {
        $css = $this->entity->security_params;

        return $css === $except ? '' : "<span class=\"label label-{$css}\">{$this->entity->security}</span>";
    }

    /**
     * @return null|string
     */
    public function category()
    {
        return $this->entity->category ? $this->entity->category->title : null;
    }

    /**
     * @return string
     */
    public function summary()
    {
        return str_limit($this->entity->summary, setting('display.summary_limit'));
    }

    /**
     * @return mixed
     */
    public function status()
    {
        $statuses = $this->entity->statuses();
        $csses = $this->entity->statusCss();
        $lists = [];
        foreach ($statuses as $status => $title) {
            $lists[] = [
                'value'      => $status,
                'text'       => $title,
                'attributes' => [
                    'data-url'  => route('backend.ebook.status', ['ebook' => $this->entity->id, 'status' => $status]),
                    'data-type' => $csses[$status],
                ],
            ];
        }

        return Form::select('status', $lists, $this->entity->status, ['class' => 'select-btngroup', 'data-size' =>
            'xs']);
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
        $src = $this->entity->featuredImageUrl($sm);
        $class = $class ? " class =\"$class\"" : '';
        $html = $title ? "<div class=\"title\">{$this->entity->name}</div>" : '';
        $width = $this->entity->config['featured_image']["width{$size}"];
        $height = $this->entity->config['featured_image']["height{$size}"];

        return "<img{$class} src=\"$src\" title=\"{$this->entity->name}\" width=\"$width\" height=\"$height\"/>{$html}";
    }

    /**
     * @return string
     */
    public function featured_image_lightbox()
    {
        $img = $this->featured_image('', true);

        return "<a href=\"{$this->entity->featured_image_url}\" data-lightbox=\"ebook-{$this->entity->id}\">{$img}</a>";
    }

    /**
     * @return string
     */
    public function title_block()
    {
        /** @var \Minhbang\Ebook\Ebook $model */
        $model = $this->entity;

        $title = Html::linkQuickUpdate(
            $model->id,
            $model->title,
            [
                'attr'      => 'title',
                'title'     => trans("ebook::common.title"),
                'class'     => 'w-lg',
                'placement' => 'top',
            ]
        );
        $info = "<small class='text-muted'>{$model->writer}, " . trans('ebook::common.publisher_id_th') . ": {$model->publisher}</small><br>";
        $info .= "<small class='text-muted'>{$this->fileicon()} {$this->filesize()} - {$this->createdAt()}</small>";

        return "<div class=\"title\">{$title}</div><div class=\"info\">{$info}</div>";
    }
    /**
     * Ex: danh sách tài liệu liên quan, tài liệu mới nhất
     *
     * @param \Minhbang\Ebook\Ebook[] $items
     * @param string $name
     * @param string $title
     * @param array $timeFormat
     *
     * @return string
     */
    /*public function linkListOf($items, $name, $title, $timeFormat = [])
    {
        $html = '';
        if (count($items)) {
            foreach ($items as $item) {
                $html .= '<li>' . $item->present()->linkWithTime($timeFormat) . '</li>';
            }
            $html = <<<"LIST"
<div class="link-list $name">
    <h3 class="link-list-title">$title</h3>
    <ul class="link-list-items">$html</ul>
</div>
LIST;
        }

        return $html;
    }*/

    /**
     * Link xem article
     *
     * @return string
     */
    /*public function link()
    {
        return Html::link($this->entity->url, $this->entity->title);
    }*/

    /**
     * Link xem article
     *
     * @param array $timeFormat
     *
     * @return string
     */
    /*public function linkWithTime($timeFormat = [])
    {
        return "<a href=\"{$this->entity->url}\">{$this->entity->title} <span class=\"time\">{$this->updatedAt($timeFormat)}</span></a>";
    }*/
}