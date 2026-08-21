<?php
namespace App\Models;
class ResourceLibrary extends LegacyAdminModel
{
    protected $table='resource_library';
    protected $casts=['settings'=>'array','is_active'=>'boolean','dated_at'=>'date'];
}
