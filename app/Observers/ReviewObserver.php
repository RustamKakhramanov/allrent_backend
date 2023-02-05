<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewObserver
{
    public function creating(Review $review)
    {
        try{
            if (!$review->reviewer_id) {
                $review->reviewer_id = Auth::user()->id ;
                $review->reviewer_type = User::class;
            }
        }catch(\Exception){
            abort(403);
        }
       

    }
}
