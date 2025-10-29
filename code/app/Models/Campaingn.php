<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaingn extends Model
{
    use HasFactory;


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
