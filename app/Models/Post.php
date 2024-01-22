<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Traits\HasRoles;

class Post extends Model
{
    use HasFactory, HasRoles;
    

    public static function boot(): void
    {
       parent::boot();
        Gate::define('view-post', function (User $user, Post $post) {
            return $user->id===$post->author->id;
        });
    }

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
