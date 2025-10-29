<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampaignRequest extends FormRequest
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
        return [
            // Step 1: Company Information
            'company_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\.\,\&\(\)]+$/u'
            ],
            'company_desc' => [
                'required',
                'string',
                'min:10',
                'max:2000'
            ],
            'company_principal_category' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'company_web_url' => [
                'nullable',
                'url',
                'max:255'
            ],
            'company_logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp,svg',
                'max:5120'
            ],
            'company_principal_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:10240'
            ],

            // Step 2: Campaign Details
            'campain_name' => [
                'required',
                'string',
                'min:5',
                'max:255'
            ],
            'campain_objective' => [
                'required',
                'string',
                'min:20',
                'max:1000'
            ],
            'campain_details' => [
                'required',
                'string',
                'min:50',
                'max:5000'
            ],
            'campain_want' => [
                'required',
                'string',
                'min:20',
                'max:2000'
            ],
            'campain_photos_required' => [
                'nullable',
                'array',
                'max:10'
            ],
            'campain_photos_required.*' => [
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120'
            ],

            // Step 3: Social Media & Content
            'campain_social_media' => [
                'required',
                'array',
                'min:1'
            ],
            'campain_social_media.*' => [
                'string',
                'in:facebook,instagram,twitter,tiktok,youtube,linkedin,snapchat,pinterest'
            ],
            'campain_social_media_content' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'do_this' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'dont_do_this' => [
                'nullable',
                'string',
                'max:1000'
            ],

            // Step 4: Targeting & Budget
            'campain_start_date' => [
                'required',
                'date',
                'after:today'
            ],
            'campain_end_date' => [
                'required',
                'date',
                'after:campain_start_date'
            ],
            'campain_proposed_budget' => [
                'required',
                'numeric',
                'min:100',
                'max:1000000'
            ],
            'date_receipt_offers_start' => [
                'nullable',
                'date',
                'after_or_equal:today'
            ],
            'date_receipt_offers_end' => [
                'nullable',
                'date',
                'after:date_receipt_offers_start',
                'before:campain_start_date'
            ],

            // Influencer Targeting
            'influencer_age_range_start' => [
                'nullable',
                'integer',
                'min:13',
                'max:100'
            ],
            'influencer_age_range_end' => [
                'nullable',
                'integer',
                'min:13',
                'max:100',
                'gte:influencer_age_range_start'
            ],
            'influencer_gender' => [
                'nullable',
                'string',
                'in:male,female,both'
            ],
            'influencer_wilaya' => [
                'nullable',
                'array',
                'max:48'
            ],
            'influencer_wilaya.*' => [
                'integer',
                'exists:wilayas,id'
            ],

            // Step 5: Payment & Company Details
            'campain_director_name' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'campain_director_email' => [
                'required',
                'email',
                'max:255'
            ],
            'campain_director_phone' => [
                'required',
                'string',
                'regex:/^[\+]?[0-9\s\-\(\)]{8,20}$/'
            ],
            'payment_method' => [
                'required',
                'string',
                'in:credit_card,bank_transfer,paypal,stripe'
            ],

            // Company Details
            'campany_name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'campany_tax_number' => [
                'nullable',
                'string',
                'max:50'
            ],
            'campany_commercial_register' => [
                'nullable',
                'string',
                'max:50'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'company_name.min' => 'Le nom de l\'entreprise doit contenir au moins 2 caractères.',
            'company_desc.required' => 'La description de l\'entreprise est obligatoire.',
            'company_desc.min' => 'La description doit contenir au moins 10 caractères.',
            'company_principal_category.required' => 'La catégorie principale est obligatoire.',
            'company_principal_category.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'company_logo.image' => 'Le logo doit être une image.',
            'company_logo.mimes' => 'Le logo doit être au format JPEG, PNG, JPG, WEBP ou SVG.',
            'company_logo.max' => 'Le logo ne doit pas dépasser 5MB.',

            'campain_name.required' => 'Le nom de la campagne est obligatoire.',
            'campain_name.min' => 'Le nom de la campagne doit contenir au moins 5 caractères.',
            'campain_objective.required' => 'L\'objectif de la campagne est obligatoire.',
            'campain_objective.min' => 'L\'objectif doit contenir au moins 20 caractères.',
            'campain_details.required' => 'Les détails de la campagne sont obligatoires.',
            'campain_details.min' => 'Les détails doivent contenir au moins 50 caractères.',
            'campain_want.required' => 'Ce que vous attendez des influenceurs est obligatoire.',
            'campain_want.min' => 'Cette section doit contenir au moins 20 caractères.',

            'campain_social_media.required' => 'Au moins un réseau social doit être sélectionné.',
            'campain_social_media.min' => 'Veuillez sélectionner au moins un réseau social.',

            'campain_start_date.required' => 'La date de début est obligatoire.',
            'campain_start_date.after' => 'La date de début doit être postérieure à aujourd\'hui.',
            'campain_end_date.required' => 'La date de fin est obligatoire.',
            'campain_end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'campain_proposed_budget.required' => 'Le budget proposé est obligatoire.',
            'campain_proposed_budget.min' => 'Le budget minimum est de 100 DZD.',
            'campain_proposed_budget.max' => 'Le budget maximum est de 1,000,000 DZD.',

            'campain_director_name.required' => 'Le nom du responsable est obligatoire.',
            'campain_director_email.required' => 'L\'email du responsable est obligatoire.',
            'campain_director_email.email' => 'L\'email doit être valide.',
            'campain_director_phone.required' => 'Le téléphone du responsable est obligatoire.',
            'campain_director_phone.regex' => 'Le numéro de téléphone n\'est pas valide.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.'
        ];
    }
}
