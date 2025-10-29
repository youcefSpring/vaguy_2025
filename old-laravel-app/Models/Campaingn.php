<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaingn extends Model
{
    use HasFactory;

    protected $fillable = [
        // Company Information
        'company_name', 'company_desc', 'company_principal_category', 'company_web_url',
        'company_logo', 'company_principal_image',

        // Campaign Details
        'campain_name', 'campain_objective', 'campain_details', 'campain_want',
        'campain_photos_required',

        // Social Media & Content
        'campain_social_media', 'campain_social_media_content', 'campain_publishing_requirement',

        // Targeting & Budget
        'campain_start_date', 'campain_end_date', 'date_receipt_offers_start', 'date_receipt_offers_end',
        'campain_proposed_budget', 'influencer_age_range', 'influencer_age_category', 'influencer_category',
        'influencer_gender', 'influencer_age', 'influencer_wilaya', 'influencer_interest',
        'influencer_public_age', 'influencer_public_gender', 'influencer_public_wilaya', 'influencer_interests',

        // Payment & Company Details
        'payment_method', 'coupon', 'campain_director_name', 'campain_director_email', 'campain_director_phone',
        'campany_name', 'campany_tax_number', 'campany_commercial_register', 'campany_financial_officer_email',
        'campany_financial_officer_phone', 'campany_street', 'campany_city', 'campany_zone',
        'campany_code_postal', 'campany_country', 'principal_category',

        // System fields
        'user_id', 'status'
    ];

    protected $casts = [
        'campain_start_date' => 'date',
        'campain_end_date' => 'date',
        'date_receipt_offers_start' => 'date',
        'date_receipt_offers_end' => 'date',
        'campain_social_media' => 'array',
        'campain_social_media_content' => 'array',
        'campain_publishing_requirement' => 'array',
        'campain_photos_required' => 'array',
        'influencer_age_range' => 'array',
        'influencer_wilaya' => 'array',
        'influencer_public_wilaya' => 'array',
    ];

    public function campain_offers()
    {
        return $this->hasMany(CampainInfluencerOffer::class,'campain_id');
    }

    public function campaignMessage()
    {
        return $this->hasMany(HiringConversation::class, 'hiring_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class,'company_principal_category','id');
    }

    public function scopePending()
    {
        return $this->where('payment_status', 1)->where('status', 0);
    }

    public function scopeCompleted()
    {
        return $this->where('payment_status', 1)->where('status', 1);
    }

    public function scopeInprogress()
    {
        return $this->where('payment_status', 1)->where('status', 2);
    }

    public function scopeJobDone()
    {
        return $this->where('payment_status', 1)->where('status', 3);
    }

    public function scopeReported()
    {
        return $this->where('payment_status', 1)->where('status', 4);
    }

    public function scopeCancelled()
    {
        return $this->where('payment_status', 1)->where('status', 5);
    }
    public function scopeRejected()
    {
        return $this->where('payment_status', 1)->where('status', 6);
    }

}
