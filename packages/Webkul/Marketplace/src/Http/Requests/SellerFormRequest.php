<?php

namespace Webkul\Marketplace\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\Slug;

class SellerFormRequest extends FormRequest
{
    /**
     * Determine if the request is authorized or not.
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

        $rules = [
            'name'                  => ['required'],
            'email'                 => ['required', 'unique:marketplace_sellers,email,'.$this->id, 'email'],
            'url'                   => ['required', 'unique:marketplace_sellers,url,'.$this->id, 'lowercase', new Slug],
            'shop_title'            => ['required'],
            'description'           => ['required'],
            'banner.*'              => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'logo.*'                => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'meta_title'            => ['nullable'],
            'meta_description'      => ['nullable'],
            'meta_keywords'         => ['nullable'],
            'address1'              => ['required'],
            'address2'              => ['nullable'],
            'phone'                 => ['required'],
            'state'                 => ['required'],
            'city'                  => ['required'],
            'country'               => ['required'],
            'postcode'              => ['required'],
            'return_policy'         => ['nullable'],
            'shipping_policy'       => ['nullable'],
            'privacy_policy'        => ['nullable'],
            'min_order_amount'      => ['nullable'],
            'google_analytics_id'   => ['nullable'],
            'twitter'               => ['nullable'],
            'facebook'              => ['nullable'],
            'linkedin'              => ['nullable'],
            'pinterest'             => ['nullable'],
        ];

        if (request()->route()->getName() == 'admin.marketplace.sellers.update') {
            $rules['allowed_product_types'] = ['array'];
            $rules['commission_enable'] = ['boolean'];
            $rules['commission_percentage'] = ['required_with:commission_enable'];
        }

        return $rules;
    }
}
