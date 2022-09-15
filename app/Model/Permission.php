<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $project_id 
 * @property int $parent_id 
 * @property string $title 
 * @property string $name 
 * @property string $path 
 * @property string $component 
 * @property string $redirect 
 * @property string $icon 
 * @property string $display_name 
 * @property string $url 
 * @property string $guard_name 
 * @property int $sort 
 * @property int $type 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'parent_id', 'title', 'name', 'path', 'component', 'redirect', 'display_name', 'url', 'icon', 'guard_name', 'sort', 'type', 'status'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'project_id' => 'integer', 'parent_id' => 'integer', 'sort' => 'integer', 'type' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}