<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_menus'; // hoặc admin_menus

    protected $fillable = [
        'title',
        'slug',
        'identifier',
        'url',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Tự động tạo slug nếu chưa truyền vào
     */
    protected static function booted()
    {
        static::saving(function ($menu) {
            if (empty($menu->slug) && !empty($menu->title)) {
                $menu->slug = Str::slug($menu->title);
            }
        });
    }

    /**
     * Quan hệ: Menu cha
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Quan hệ: Menu con
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order', 'ASC');
    }

    /**
     * Scope: chỉ menu active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'active');
    }
}
