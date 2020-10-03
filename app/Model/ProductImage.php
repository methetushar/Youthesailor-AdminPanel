<?php

/**
 * @Oshit Sutra Dhar
 */

namespace App\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductImage extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return url('/') . "/public" . $value;
        }
        return null;
    }



    //--------------------------------------------------------
    // LOG ACTIVITY
    //--------------------------------------------------------
    protected static $logAttributes = ['*'];
    protected static $logName = 'ProductImage';
    public function getDescriptionForEvent(string $eventName): string
    {
        $name = Auth::guard('admin')->user()->name;
        return "{$name} have {$eventName} the ProductImage";
    }
}
