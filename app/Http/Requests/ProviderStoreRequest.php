<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderStoreRequest extends FormRequest
{
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
        return [
            'name' => 'required|string',
            'provider' => 'required|string|in:gorilla,txtria,7gnetwork,twilio,textcalibur,simpletexting',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'cost' => 'required|numeric',
        ];
    }
}
