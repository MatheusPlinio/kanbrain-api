<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'column_id',
        'parent_id',
        'created_by',
        'assigned_by',
        'title',
        'description',
        'order',
        'is_completed'
    ];
    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
