<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getAll(): Collection
    {
        return Task::all();
    }

    public function getMyTasks(int $userId): Collection
    {
        return Task::where('user_id', $userId)->get();
    }


    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function getById(int $id): Task
    {
        return Task::find($id);
    }

    public function update(array $data, int $id): Task
    {
        $task = $this->getById($id);
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
