<?php

namespace App\Http\Services;

use App\Http\Repositories\TaskRepository;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    protected $repository;
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTasks(int $userId): Collection
    {
        return $this->repository->getAll($userId);
    }

    public function getMyTasks(int $userId): Collection
    {
        return $this->repository->getMyTasks($userId);
    }

    public function createTask(array $data): Task
    {
        return $this->repository->create($data);
    }

    public function getTaskById(int $id): Task
    {
        $task =  $this->repository->getById($id);
        if (!$task) {
            throw new HttpResponseException(
                response()->json(['message' => 'Task not found.'], Response::HTTP_NOT_FOUND)
            );
        }

        return $task;
    }

    public function updateTask(int $id, array $data)
    {
        $task = $this->getTaskById($id);
        if (!$task) {
            \Log::error("Task not found with id $id");
            throw new HttpResponseException(
                response()->json(['message' => 'Task not found.'], Response::HTTP_NOT_FOUND)
            );
        }

        $userId = auth()->user()->id;
        if ($task->user_id !== $userId) {
            throw new HttpResponseException(
                response()->json(['message' => 'Access denied to update this task.'], Response::HTTP_FORBIDDEN)
            );
        }

        return $this->repository->update($data, $id);
    }

    public function deleteTask(int $id): void
    {
        $task = $this->getTaskById($id);
        if (!$task) {
            throw new HttpResponseException(
                response()->json(['message' => 'Task not found.'], Response::HTTP_NOT_FOUND)
            );
        }

        $userId = auth()->user()->id;
        if ($task->user_id !== $userId) {
            throw new HttpResponseException(
                response()->json(['message' => 'Access denied to delete this task.'], Response::HTTP_FORBIDDEN)
            );
        }

        $this->repository->delete($task);
    }
}
