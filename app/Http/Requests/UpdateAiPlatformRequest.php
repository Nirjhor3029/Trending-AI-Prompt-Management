<?php

namespace App\Http\Requests;

use App\Models\AiPlatform;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAiPlatformRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ai_platform_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'nullable',
            ],
        ];
    }
}
