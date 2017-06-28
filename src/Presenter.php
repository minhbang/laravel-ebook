<?php

namespace Minhbang\Ebook;

use Laracasts\Presenter\Presenter as BasePresenter;
use Html;
use Minhbang\File\File;
use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
use Minhbang\Status\StatusPresenter;

/**
 * Class Presenter
 *
 * @property-read Ebook $entity
 * @package Minhbang\Ebook
 */
class Presenter extends BasePresenter {
    use DatetimePresenter;
    use StatusPresenter;

    /**
     * @return null|string
     */
    public function category() {
        return $this->entity->category ? $this->entity->category->title : null;
    }

    /**
     * @return string
     */
    public function summary() {
        return str_limit( $this->entity->summary, setting( 'display.summary_limit' ) );
    }

    /**
     * @param string $except
     *
     * @return string
     */
    public function security( $except = null ) {
        $css = $this->entity->security_params ?: 'default';

        return $except && ( $css === $except ) ? '' : "<span class=\"label label-{$css}\">{$this->entity->security_title}</span>";
    }

    /**
     * @param string|null $class
     * @param bool $sm
     * @param bool $title
     * @param string $size
     *
     * @return string
     */
    public function featured_image( $class = 'img-responsive', $sm = false, $title = false, $size = '_md' ) {
        $src = $this->entity->featuredImageUrl( $sm );
        $class = $class ? " class =\"$class\"" : '';
        $html = $title ? "<div class=\"title\">{$this->entity->title}</div>" : '';
        $width = $this->entity->config['featured_image']["width{$size}"];
        $height = $this->entity->config['featured_image']["height{$size}"];
        $src = $src ? "src=\"$src\"" : "data-src=\"holder.js/48x72\"";

        return "<img{$class} $src title=\"{$this->entity->title}\" width=\"$width\" height=\"$height\"/>{$html}";
    }

    /**
     * @return string
     */
    public function featured_image_lightbox() {
        $img = $this->featured_image( '', true, false, '_sm' );

        return "<a href=\"{$this->entity->featured_image_url}\" data-lightbox=\"ebook-{$this->entity->id}\">{$img}</a>";
    }

    /**
     * @return string
     */
    public function title_block() {
        $title = $this->entity->isReady( 'update' ) ? Html::linkQuickUpdate(
            $this->entity->id,
            $this->entity->title,
            [
                'attr'      => 'title',
                'title'     => trans( "ebook::common.title" ),
                'class'     => 'w-lg',
                'placement' => 'top',
            ]
        ) : $this->entity->title;
        $info = "<small class='text-muted'>{$this->entity->writer_title}, " . trans( 'ebook::common.publisher_id_th' ) . ": {$this->entity->publisher_title}</small><br>";

        //$info .= "<small class='text-muted'>{$this->fileicon()} {$this->filesize()} - {$this->createdAt()}</small>";

        return "<div class=\"title\">{$title}</div><div class=\"info\">{$info}</div>";
    }

    /**
     * @return string
     */
    public function title_block_1() {
        $title = '<a href="' . route( 'ilib.backend.ebook.show',
                [ 'ebook' => $this->entity->id ] ) . '">' . $this->entity->title . '</a>';
        $info = "<small class='text-muted'>- {$this->entity->writer_title}, " . trans( 'ebook::common.publisher_id_th' ) . ": {$this->entity->publisher_title}</small><br>";

        //$info .= "<small class='text-muted'>{$this->fileicon()} {$this->filesize()} - {$this->createdAt()}</small>";
        $info .= "<small class='text-muted'>- {$this->createdAt()}</small>";

        return "<div class=\"title\">{$title}</div><div class=\"info\">{$info}</div>";
    }

    /**
     * Danh sách file của ebook này
     *
     * @param string $viewRoute
     *
     * @param array $params
     *
     * @return string
     */
    public function files( $viewRoute = 'backend.file.preview', $params = [] ) {
        $files = $this->entity->files->map( function ( File $file ) use ( $viewRoute, $params ) {
            return '<li>' . $file->forReturn( $viewRoute, $params )['title'] . '</li>';
        } )->all();

        return "<ol>" . implode( "", $files ) . "</ol>";
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