<?php 
namespace App\Models\Honestee\VueCodeGen;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

use Spatie\Permission\Models\Permission as SpatiePermission;


class Permission extends SpatiePermission
{

    use UsesTenantConnection;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var  array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var  array
     */
    protected $casts = [
        ''
    ];

 
} ?>