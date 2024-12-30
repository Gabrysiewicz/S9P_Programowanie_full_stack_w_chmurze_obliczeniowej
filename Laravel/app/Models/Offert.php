<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offert extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'surname', 'voivodeship', 'city', 'company', 'profession', 'workplace', 'profile-picture', 'youtube', 'facebook', 'instagrem', 'tiktok', 'twitter', 'description'];

    public function scopeFilter($query, array $filters){
        // ddd($filters);
        if($filters['profession'] ?? false){ // not false
            $query->where('profession', 'like', '%'. request('profession') . '%');
        }

        if($filters['search'] ?? false){ // not false
            $query->where('voivodeship', 'like', '%'. request('search') . '%')
                  ->orWhere('city', 'like', '%'. request('search') . '%');
        }
    }
    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }
    
}
