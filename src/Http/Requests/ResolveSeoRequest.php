<?php

declare(strict_types=1);

namespace Jenlut\YoastSeoLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ResolveSeoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'required_with:id', 'string', 'max:100'],
            'id' => ['sometimes', 'required_with:type', 'string', 'max:191'],
            'url' => ['sometimes', 'nullable', 'url:http,https', 'max:2048'],
            'title' => ['sometimes', 'nullable', 'string', 'max:500'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'canonical' => ['sometimes', 'nullable', 'url:http,https', 'max:2048'],
            'robots' => ['sometimes', 'nullable', 'string', 'max:500'],
            'open_graph' => ['sometimes', 'array'],
            'twitter' => ['sometimes', 'array'],
            'schema' => ['sometimes', 'array'],
        ];
    }
}
