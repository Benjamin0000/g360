<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
class ShopCategory extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = [
        'name',
        'slug',
    ];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>true
            ]
        ];
    }
    public function shops()
    {
        return $this->hasMany(Shop::class, 'shop_category_id');
    }
}
 