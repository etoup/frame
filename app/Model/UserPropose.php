<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $user_id 
 * @property int $department_id 
 * @property int $job_id 
 * @property string $real_name 
 * @property string $department_name 
 * @property string $job_name 
 * @property string $project_name 
 * @property string $problem 
 * @property string $problem_path 
 * @property int $problem_path_type 
 * @property string $measure 
 * @property string $measure_path 
 * @property int $measure_path_type 
 * @property string $effect 
 * @property string $effect_path 
 * @property int $effect_path_type 
 * @property string $remark 
 * @property string $type_name 
 * @property int $type 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class UserPropose extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_propose';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status', 'remark'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'department_id' => 'integer', 'job_id' => 'integer', 'problem_path_type' => 'integer', 'measure_path_type' => 'integer', 'effect_path_type' => 'integer', 'type' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}