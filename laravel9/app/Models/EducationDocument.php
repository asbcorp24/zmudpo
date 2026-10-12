<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EducationDocument extends Model
{
 protected $guarded=[];
 protected $casts=['issued_at'=>'date','meta'=>'array'];
 public function user(){return $this->belongsTo(User::class);}
 public function program(){return $this->belongsTo(Program::class);} public function template(){return $this->belongsTo(CertificateTemplate::class,'certificate_template_id');}
}
