<?php

/**
 * @Oshit Sutra Dhar
 */

namespace App\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class VariationProduct extends Model
{
    use LogsActivity;
    protected $guarded = [];

    public function color()
    {
        return $this->belongsTo(Color::class)->select('id', 'name', 'code');
    }
    public function size()
    {
        return $this->belongsTo(Size::class)->select('id', 'name');
    }



    //--------------------------------------------------------
    // LOG ACTIVITY
    //--------------------------------------------------------
    protected static $logAttributes = ['*'];
    protected static $logName = 'VariationProduct';
    public function getDescriptionForEvent(string $eventName): string
    {
        $name = Auth::guard('admin')->user()->name;
        return "{$name} have {$eventName} the VariationProduct";
    }
}
