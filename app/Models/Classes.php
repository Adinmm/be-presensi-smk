<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Classes extends Model {

    protected $fillable = [
        'id',
        'nama_kelas',
        'jurusan',
        'tingkat',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function students() {
        return $this->hasMany(Students::class, 'class_id');
    }

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
