<?php

namespace Modules\Organization\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @class AdditionalRequest
 * @package Modules\Organization\Http\Requests\Common
 */
class AdditionalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
