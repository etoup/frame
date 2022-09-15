<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
/**
 * @property int $id 
 * @property int $project_id 
 * @property string $title 
 * @property string $content 
 * @property string $remark 
 * @property string $files 
 * @property int $type 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Notice extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notice';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'title', 'content', 'remark', 'files', 'type', 'status'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'project_id' => 'integer', 'type' => 'integer', 'status' => 'integer', 'files' => 'array', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}