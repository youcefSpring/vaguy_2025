<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|min:50|max:2000',
            'budget' => 'required|numeric|min:10|max:999999',
            'category_id' => 'required|integer|exists:categories,id',
            'campaign_type' => 'required|string|in:sponsored_post,story,video_content,product_review',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'minimum_followers' => 'nullable|integer|min:100|max:10000000',
            'target_location' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
        ];

        // If this is an update request, make some fields optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] = 'sometimes|required|string|max:255|min:10';
            $rules['description'] = 'sometimes|required|string|min:50|max:2000';
            $rules['budget'] = 'sometimes|required|numeric|min:10|max:999999';
            $rules['category_id'] = 'sometimes|required|integer|exists:categories,id';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Campaign title is required.',
            'title.min' => 'Campaign title must be at least 10 characters.',
            'title.max' => 'Campaign title cannot exceed 255 characters.',
            'description.required' => 'Campaign description is required.',
            'description.min' => 'Campaign description must be at least 50 characters.',
            'description.max' => 'Campaign description cannot exceed 2000 characters.',
            'budget.required' => 'Campaign budget is required.',
            'budget.min' => 'Campaign budget must be at least $10.',
            'budget.max' => 'Campaign budget cannot exceed $999,999.',
            'category_id.required' => 'Please select a campaign category.',
            'category_id.exists' => 'Selected category does not exist.',
            'campaign_type.required' => 'Please select a campaign type.',
            'campaign_type.in' => 'Invalid campaign type selected.',
            'start_date.required' => 'Campaign start date is required.',
            'start_date.after_or_equal' => 'Campaign start date cannot be in the past.',
            'end_date.required' => 'Campaign end date is required.',
            'end_date.after' => 'Campaign end date must be after the start date.',
            'minimum_followers.min' => 'Minimum followers must be at least 100.',
            'minimum_followers.max' => 'Minimum followers cannot exceed 10,000,000.',
            'image.image' => 'Campaign image must be a valid image file.',
            'image.mimes' => 'Campaign image must be in JPEG, PNG, JPG, or WebP format.',
            'image.max' => 'Campaign image size cannot exceed 5MB.',
            'tags.max' => 'Tags cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'campaign_type' => 'campaign type',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'minimum_followers' => 'minimum followers',
            'target_location' => 'target location',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
            'description' => trim($this->description),
            'target_location' => $this->target_location ? trim($this->target_location) : null,
            'tags' => $this->tags ? trim($this->tags) : null,
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation: Check if end date is reasonable (not more than 1 year from start)
            if ($this->start_date && $this->end_date) {
                $start = \Carbon\Carbon::parse($this->start_date);
                $end = \Carbon\Carbon::parse($this->end_date);

                if ($start->diffInMonths($end) > 12) {
                    $validator->errors()->add('end_date', 'Campaign duration cannot exceed 12 months.');
                }
            }

            // Custom validation: Check if tags are properly formatted
            if ($this->tags) {
                $tags = explode(',', $this->tags);
                if (count($tags) > 10) {
                    $validator->errors()->add('tags', 'Cannot have more than 10 tags.');
                }

                foreach ($tags as $tag) {
                    if (strlen(trim($tag)) < 2) {
                        $validator->errors()->add('tags', 'Each tag must be at least 2 characters long.');
                        break;
                    }
                }
            }
        });
    }
}