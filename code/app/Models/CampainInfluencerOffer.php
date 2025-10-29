<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampainInfluencerOffer extends Model
{
    use HasFactory;

    protected $appends = ['status_name'];

    public function getStatusNameAttribute()
    {
        return $this->convertStatusToName($this->status);
    }

    private function convertStatusToName($status)
    {
        switch ($status) {
            case 1:
                return 'Accepté';
            case 0:
                return 'Refusé';
            case 3:
                return 'Confirmée';
            case 4:
                return 'Job done';
            case 5 :
                    return "Terminé";
            case 6 :
                    return "Reported to admin";
            default:
                return 'En attente';
        }


        }


    public function campain_notifs()
    {
        return $this->hasMany(CampainInfluencerOffer::class,'camapin_offer_id');
    }

    public function influencer(){
        return $this->belongsTo(Influencer::class,'influencer_id');
    }
}
