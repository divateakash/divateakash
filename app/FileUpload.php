<?php

namespace App;

use App\Jobs\ProcessFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'headers',
        'mapping',
        'duplicates',
        'rejected',
        'landlines',
        'processed_at',
        'catalog_id',
        'team_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mapping' => 'array',
        'headers' => 'array',
        'catalog_id' => 'integer',
        'team_id' => 'integer',
    ];

    protected $dates = [
        'processed_at',
    ];

    public function catalog()
    {
        return $this->belongsTo(\App\Catalog::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function process()
    {
        ProcessFileUpload::dispatch($this);
    }

    public function url()
    {
        return url(Storage::url($this->path));
    }
}
