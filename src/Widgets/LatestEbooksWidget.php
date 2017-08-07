<?php namespace Minhbang\Ebook\Widgets;

use Minhbang\Layout\WidgetTypes\WidgetType;
use Minhbang\Ebook\Ebook;
use Minhbang\Category\Category;
use CategoryManager;

/**
 * Class LatestEbooksWidget
 *
 * @package Minhbang\Ebook\Widgets
 */
class LatestEbooksWidget extends WidgetType
{
    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend($widget)
    {
        $category = $this->getCategory($widget);
        $title = $category ? ($category->isRoot() ? '' : $category->title) : $widget;

        return parent::titleBackend($title);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return CategoryManager::of(Ebook::class)->selectize();
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return Category|null
     */
    protected function getCategory($widget)
    {
        return $widget->data['category_id'] ? Category::find($widget->data['category_id']) : CategoryManager::of(Ebook::class)->node();
    }

    /**
     * @return array
     */
    public function formOptions()
    {
        return ['width' => 'large'] + parent::formOptions();
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'ebook::widget.latest_ebooks_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function title($widget)
    {
        $title = parent::title($widget);
        if ($widget->data['category_id'] && $widget->data['show_link_category'] && ($category = $this->getCategory($widget))) {
            $title = '<a class="link-category" href="'.route('ilib.category.show', ['slug' => $category->slug]).'">'.$title.'</a>';
        }

        return $title;
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content($widget)
    {
        if ($category = $this->getCategory($widget)) {
            $ebooks = Ebook::queryDefault()->ready('read')->withEnumTitles()->withCategoryTitle()->orderUpdated()->categorized($category)->take($widget->data['limit'])->get();

            return view('ebook::widget.latest_ebooks_output', compact('widget', 'ebooks'))->render();
        } else {
            return '';
        }
    }

    /**
     * @return array
     */
    protected function dataAttributes()
    {
        return [
            [
                'name' => 'category_id',
                'title' => trans('ebook::widget.latest_ebooks.category_id'),
                'rule' => 'integer|nullable',
                'default' => null,
            ],
            [
                'name' => 'show_link_category',
                'title' => trans('ebook::widget.latest_ebooks.show_link_category'),
                'rule' => 'integer|nullable',
                'default' => 0,
            ],
            [
                'name' => 'limit',
                'title' => trans('ebook::widget.latest_ebooks.limit'),
                'rule' => 'required|integer|min:1',
                'default' => 6,
            ],
            [
                'name' => 'item_style',
                'title' => trans('ebook::widget.latest_ebooks.item_style'),
                'rule' => 'required|string|in:th,list',
                'default' => 'th',
            ],
            [
                'name' => 'item_css',
                'title' => trans('ebook::widget.latest_ebooks.item_css'),
                'rule' => 'nullable|max:255',
                'default' => '',
            ],
        ];
    }
}