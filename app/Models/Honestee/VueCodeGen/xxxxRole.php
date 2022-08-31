<?php 
namespace App\Models\Honestee\VueCodeGen;

use Illuminate\Database\Eloquent\Model;
use App\Models\Honestee\VueCodeGen\User;


class Role extends Model
{

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


    protected $fillable = [
        'name', 'description'
    ];

    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

 
} ?>