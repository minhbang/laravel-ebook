<?php
namespace Minhbang\Ebook;

use Minhbang\Kit\Extensions\Request as BaseRequest;

/**
 * Class Request
 *
 * @package Minhbang\Ebook
 */
class Request extends BaseRequest
{
    public $trans_prefix = 'ebook::common';
    public $rules = [
        'title'        => 'required|max:255',
        'slug'         => 'required|max:255|alpha_dash',
        'summary'      => 'required',
        'pages'        => 'integer',
        'pyear'        => 'integer',
        'language_id'  => 'required',
        'security_id'  => 'required',
        'writer_id'    => 'required',
        'publisher_id' => 'required',
        'filename'     => 'mimes:pdf|max:40960', // 40 Mb = 40*1024 Kb
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var \Minhbang\Ebook\Ebook $ebook */
        if ($ebook = $this->route('ebook')) {
            //update Ebook
        } else {
            //create Ebook
            $this->rules['filename'] .= '|required';
        }
        return $this->rules;
    }

}
