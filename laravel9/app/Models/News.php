<?php namespace App\Models; class News extends LegacyAdminModel {protected $casts=['settings'=>'array','options'=>'array','published_at'=>'date','is_active'=>'boolean'];}
