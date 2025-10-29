<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaingns';

    protected $fillable = [
        'user_id',
        'company_logo',
        'company_name',
        'company_desc',
        'company_principal_image',
        'company_principal_category',
        'company_web_url',
        'campain_name',
        'campain_objective',
        'campain_details',
        'campain_want',
        'campain_photos_required',
        'campain_social_media_content',
        'campain_publishing_requirement',
        'campain_start_date',
        'campain_end_date',
        'payment_method',
        'coupon',
        'date_receipt_offers_start',
        'date_receipt_offers_end',
        'campain_proposed_budget',
        'campain_director_name',
        'campain_director_email',
        'campain_director_phone',
        'campany_name',
        'campany_tax_number',
        'campany_commercial_register',
        'campany_financial_officer_email',
        'campany_financial_officer_phone',
        'campain_social_media',
        'principal_category',
        'influencer_age_range',
        'influencer_age_category',
        'influencer_category',
        'influencer_gender',
        'influencer_age',
        'influencer_wilaya',
        'influencer_interest',
        'influencer_public_age',
        'influencer_public_gender',
        'influencer_public_wilaya',
        'influencer_interests',
        'campany_street',
        'campany_city',
        'campany_zone',
        'campany_code_postal',
        'campany_country',
        'status'
    ];

    protected $casts = [
        'campain_start_date' => 'date',
        'campain_end_date' => 'date',
        'date_receipt_offers_start' => 'date',
        'date_receipt_offers_end' => 'date',
        'campain_social_media_content' => 'array',
        'campain_social_media' => 'array',
        'principal_category' => 'array',
        'influencer_interest' => 'array',
        'influencer_interests' => 'array',
        'influencer_public_wilaya' => 'array',
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors & Mutators
    public function getCompanyLogoAttribute($value)
    {
        return $value ? getImage('assets/images/campaigns/logos/' . $value) : null;
    }

    public function getCompanyPrincipalImageAttribute($value)
    {
        return $value ? getImage('assets/images/campaigns/images/' . $value) : null;
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}