<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Qbhy\HyperfAuth\Authenticatable;
use Qbhy\HyperfAuth\AuthAbility;
use Hyperf\Database\Model\SoftDeletes;
/**
 * @property int $id 
 * @property int $project_id 
 * @property int $department_id 
 * @property string $role 
 * @property string $username 
 * @property string $password 
 * @property string $mobile 
 * @property string $open_id 
 * @property string $union_id 
 * @property string $remember_token 
 * @property string $real_name 
 * @property string $nick_name 
 * @property string $avatar_url 
 * @property string $sex 
 * @property string $birthday 
 * @property string $email 
 * @property string $telephone 
 * @property string $remark 
 * @property int $agreed 
 * @property int $type 
 * @property int $super 
 * @property int $status 
 * @property string $type_updated_at 
 * @property string $last_login_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class User extends Model implements Authenticatable
{
    use AuthAbility, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $hidden = ['password'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'project_id' => 'integer', 'department_id' => 'integer', 'agreed' => 'integer', 'type' => 'integer', 'super' => 'integer', 'status' => 'integer', 'role' => 'array', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


}