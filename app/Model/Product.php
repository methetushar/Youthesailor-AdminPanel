<?php

/**
 * @Oshit Sutra Dhar
 */

namespace App\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;
    protected $guarded = [];




    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return url('/') . "/public/storage/" . $value;
        }
        return null;
    }



    // relation with product image
    public function product_images()
    {
        return $this->hasMany(ProductImage::class)->select('image', 'product_id', 'id');
    }
    // relation with variation product
    public function variation()
    {
        return $this->hasMany(VariationProduct::class)
            ->select('product_id', 'color_id', 'size_id', 'qty', 'amount');
    }
    //--------------------------------------------------------
    // LOG ACTIVITY
    //--------------------------------------------------------
    protected static $logAttributes = ['*'];
    protected static $logName = 'Product';
    public function getDescriptionForEvent(string $eventName): string
    {
        $name = Auth::guard('admin')->user()->name;
        return "{$name} have {$eventName} the Product";
    }
}
