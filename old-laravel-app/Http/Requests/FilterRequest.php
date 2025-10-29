<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Search filters
            'search' => 'nullable|string|max:255|min:2',

            // Category filters
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'category' => 'nullable|array',
            'category.*' => 'integer|exists:categories,id',
            'categoryId' => 'nullable|integer|exists:categories,id',

            // Price/budget filters
            'min' => 'nullable|numeric|min:0|max:999999',
            'max' => 'nullable|numeric|min:0|max:999999',
            'followers_min' => 'nullable|integer|min:0|max:100000000',
            'followers_max' => 'nullable|integer|min:0|max:100000000',

            // Sorting
            'sort' => 'nullable|string|in:asc,desc,price_asc,price_desc,followers_asc,followers_desc',

            // Social media filters
            'social' => 'nullable|array',
            'social.*' => 'string|in:instagram,facebook,twitter,youtube,tiktok,linkedin',

            // Gender filters
            'gender_audience' => 'nullable|string|in:men,women,male,female',
            'gender_influencers' => 'nullable|string|in:male,female',
            'gender_audience_pourcentage' => 'nullable|numeric|min:0|max:100',

            // Age filters
            'age' => 'nullable|integer|min:13|max:80',
            'audience_age' => 'nullable|integer|in:13,18,25,35,45,55,65',
            'audience_age_pourcentage' => 'nullable|numeric|min:0|max:100',

            // Location filters
            'wilaya_audience' => 'nullable|array',
            'wilaya_audience.*' => 'string|max:100',
            'wilaya_interactions_pourcentage' => 'nullable|numeric|min:0|max:100',

            // Language filters
            'lang' => 'nullable|array',
            'lang.*' => 'string|in:arabic,french,english,spanish,german,italian',

            // Interaction filters
            'average_interactions' => 'nullable|string|in:low,medium,high',
            'completed_jobs' => 'nullable|integer|min:0|max:10000',

            // Rating filters
            'rating' => 'nullable|numeric|min:1|max:5',

            // Tag filters
            'tagId' => 'nullable|integer|exists:tags,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'search.min' => 'Search term must be at least 2 characters.',
            'search.max' => 'Search term cannot exceed 255 characters.',
            'categories.*.exists' => 'One or more selected categories do not exist.',
            'category.*.exists' => 'One or more selected categories do not exist.',
            'categoryId.exists' => 'Selected category does not exist.',
            'min.numeric' => 'Minimum price must be a valid number.',
            'max.numeric' => 'Maximum price must be a valid number.',
            'min.max' => 'Minimum price cannot exceed 999,999.',
            'max.max' => 'Maximum price cannot exceed 999,999.',
            'followers_min.max' => 'Minimum followers cannot exceed 100,000,000.',
            'followers_max.max' => 'Maximum followers cannot exceed 100,000,000.',
            'sort.in' => 'Invalid sort option selected.',
            'social.*.in' => 'Invalid social media platform selected.',
            'gender_audience.in' => 'Invalid gender option selected.',
            'gender_influencers.in' => 'Invalid influencer gender option selected.',
            'gender_audience_pourcentage.max' => 'Gender percentage cannot exceed 100%.',
            'age.min' => 'Age must be at least 13.',
            'age.max' => 'Age cannot exceed 80.',
            'audience_age.in' => 'Invalid audience age range selected.',
            'audience_age_pourcentage.max' => 'Age percentage cannot exceed 100%.',
            'lang.*.in' => 'Invalid language selected.',
            'average_interactions.in' => 'Invalid interaction level selected.',
            'completed_jobs.max' => 'Completed jobs filter cannot exceed 10,000.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'tagId.exists' => 'Selected tag does not exist.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'categoryId' => 'category',
            'followers_min' => 'minimum followers',
            'followers_max' => 'maximum followers',
            'gender_audience' => 'audience gender',
            'gender_influencers' => 'influencer gender',
            'gender_audience_pourcentage' => 'gender percentage',
            'audience_age' => 'audience age',
            'audience_age_pourcentage' => 'age percentage',
            'wilaya_audience' => 'audience location',
            'wilaya_interactions_pourcentage' => 'location interaction percentage',
            'average_interactions' => 'interaction level',
            'completed_jobs' => 'completed jobs',
            'tagId' => 'tag',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean search input
        if ($this->has('search')) {
            $this->merge([
                'search' => trim($this->search)
            ]);
        }

        // Convert single values to arrays where expected
        if ($this->has('social') && !is_array($this->social)) {
            $this->merge([
                'social' => [$this->social]
            ]);
        }

        if ($this->has('lang') && !is_array($this->lang)) {
            $this->merge([
                'lang' => [$this->lang]
            ]);
        }

        if ($this->has('wilaya_audience') && !is_array($this->wilaya_audience)) {
            $this->merge([
                'wilaya_audience' => [$this->wilaya_audience]
            ]);
        }

        // Normalize numeric values
        if ($this->has('min')) {
            $this->merge(['min' => (float) $this->min]);
        }

        if ($this->has('max')) {
            $this->merge(['max' => (float) $this->max]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that min is not greater than max
            if ($this->min && $this->max && $this->min > $this->max) {
                $validator->errors()->add('min', 'Minimum price cannot be greater than maximum price.');
            }

            // Validate followers range
            if ($this->followers_min && $this->followers_max && $this->followers_min > $this->followers_max) {
                $validator->errors()->add('followers_min', 'Minimum followers cannot be greater than maximum followers.');
            }

            // Validate that at least one filter is provided
            $hasFilter = $this->search || $this->categories || $this->category || $this->categoryId ||
                        $this->min || $this->max || $this->social || $this->gender_audience ||
                        $this->age || $this->lang || $this->rating || $this->tagId;

            if (!$hasFilter && !$this->isMethod('GET')) {
                $validator->errors()->add('filters', 'At least one filter must be provided.');
            }
        });
    }
}