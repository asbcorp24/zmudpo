<?php
namespace App\Models;
class CertificateTemplate extends LegacyAdminModel
{
 protected $casts=['settings'=>'array','is_default'=>'boolean','is_active'=>'boolean'];
 public function documents(){return $this->hasMany(EducationDocument::class);}
}
