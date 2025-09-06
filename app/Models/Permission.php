<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    public function group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id', 'id');
    }
}
