<?php

namespace App\Models;

class ProgramType extends LegacyAdminModel
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'program_type_id');
    }
}
