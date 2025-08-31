<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Services\TaskService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): JsonResponse
    {
        $userId = auth()->user()->id;
        $tasks = $this->taskService->getAllTasks($userId);
        return response()->json($tasks, Response::HTTP_OK);
    }

    public function myTasks(): JsonResponse
    {
        $userId = auth()->user()->id;
        $tasks = $this->taskService->getMyTasks($userId);
        return response()->json($tasks, Response::HTTP_OK);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;
            $task = $this->taskService->createTask($data);
            return response()->json($task, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Create task error.", ["message" => $e->getMessage()]);
            return response()->json([
                'message' => 'Task creation error. Please try again later.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            return response()->json($task, Response::HTTP_OK);
        } catch (HttpResponseException $e) {
            \Log::error('Task viewing error.', ['message' => $e->getMessage()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error("Task viewing error.", ["message" => $e->getMessage()]);
            return response()->json([
                'message' => 'Task viewing error. Please try again later.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $task = $this->taskService->updateTask($id, $data);
            return response()->json($task, Response::HTTP_OK);
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Task update error.', ['message' => $e->getMessage()]);
            return response()->json(
                ['error' => 'Failed to update task data. Please try again later.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);
            return response()->json(['Task was deleted successfully.'], Response::HTTP_OK);
        } catch (HttpResponseException $e) {
            \Log::error('Task viewing error.', ['message' => $e->getMessage()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error("Task deleting error.", ["message" => $e->getMessage()]);
            return response()->json([
                'message' => 'Task deleting error. Please try again later.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
