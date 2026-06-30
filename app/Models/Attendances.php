<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attendances extends Model {
    protected $fillable = [
        'id',
        'student_id',
        'class_id',
        'date',
        'status',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function student() {
        return $this->belongsTo(Students::class, 'student_id');
    }

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
