<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketProgressRequest extends FormRequest
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
            'status_id'    => ['required'],
            'description'  => ['required', 'min:10'],
            'item_id'      => ['array'],
            'item_id.*'    => ['required'],
            'keterangan'   => ['array'],
            'keterangan.*' => ['required', 'min:5'],
        ];
    }

    public function messages()
    {
        $messages = [];
        if ($this->get('item_id')) {
            foreach ($this->get('item_id') as $key => $val) {
                $messages["item_id.$key.required"] = "The item field is required";
            }
        }

        if ($this->get('keterangan')) {
            foreach ($this->get('keterangan') as $key => $val) {
                $messages["keterangan.$key.required"] = "The description field is required";
                $messages["keterangan.$key.min"] = "The description field must be at least :min characters.";
            }
        }
        return $messages;
    }
}
