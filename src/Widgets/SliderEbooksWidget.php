<?php namespace Minhbang\Ebook\Widgets;

use Minhbang\Layout\WidgetTypes\WidgetType;
use Minhbang\Ebook\Ebook;
use Minhbang\Category\Category;
use CategoryManager;

/**
 * Class SliderEbooksWidget
 *
 * @package Minhbang\Ebook\Widgets
 */
class SliderEbooksWidget extends WidgetType
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
        return 'ebook::widget.slider_ebooks_form';
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
            $query = Ebook::queryDefault()->ready('read')->withEnumTitles()->withCategoryTitle()->orderUpdated()->categorized($category);
            $ebooks = $widget->data['query_type'] == 'featured' ? $query->featured($widget->data['limit'])->get() : $query->take($widget->data['limit'])->get();

            return view('ebook::widget.slider_ebooks_output', compact('widget', 'ebooks'))->render();
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
                'title' => trans('ebook::widget.slider_ebooks.category_id'),
                'rule' => 'integer|nullable',
                'default' => null,
            ],
            [
                'name' => 'query_type',
                'title' => trans('ebook::widget.slider_ebooks.query_type'),
                'rule' => 'required|string|in:featured,latest',
                'default' => 'featured',
            ],
            [
                'name' => 'limit',
                'title' => trans('ebook::widget.slider_ebooks.limit'),
                'rule' => 'required|integer|min:1',
                'default' => 6,
            ],
        ];
    }
}