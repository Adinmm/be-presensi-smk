<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Students extends Model {

    protected $fillable = [
        'id',
        'nis',
        'jenis_kelamin',
        'nama_lengkap',
        'no_telepon',
        'class_id',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function kelas() {
        return $this->belongsTo(Classes::class, 'class_id');
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
