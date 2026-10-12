<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItemResponse extends Model
{
    protected $guarded=[];
    protected $casts=[
        'files'=>'array',
        'meta'=>'array',
        'submitted_at'=>'datetime',
        'reviewed_at'=>'datetime',
        'score'=>'decimal:2',
    ];

    public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
    public function user(){return $this->belongsTo(User::class);}
    public function reviewer(){return $this->belongsTo(User::class,'reviewed_by');}
}
