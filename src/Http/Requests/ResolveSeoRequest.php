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
