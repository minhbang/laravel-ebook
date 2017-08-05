<?php namespace Minhbang\Ebook\Widgets;

use Minhbang\Category\Widgets\CategoryWidgetType;
use Minhbang\Ebook\Ebook;

/**
 * Class EbookCategoryWidget
 *
 * @package Minhbang\Ebook\Widgets
 */
class EbookCategoryWidget extends CategoryWidgetType
{
    protected function categoryType()
    {
        return Ebook::class;
    }
}