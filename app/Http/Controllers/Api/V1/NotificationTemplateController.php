<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationTemplateRequest;
use App\Http\Requests\UpdateNotificationTemplateRequest;
use App\Repositories\Interfaces\NotificationTemplateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotificationTemplateController extends Controller
{
    public function __construct(
        private NotificationTemplateRepositoryInterface $repository
    ) {}

    public function index(): JsonResponse
    {
        try {
            $templates = $this->repository->all();
            return $this->success($templates, 'Templates Fetched Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to fetch templates', $e->getCode(), $e->getTrace());
        }
    }

    public function store(StoreNotificationTemplateRequest $request): JsonResponse
    {
        try {
            $template = $this->repository->create($request->validated());
            return $this->success($template, 'Template Created Successfully', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->error('Failed to create template', $e->getCode(), $e->getTrace());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $template = $this->repository->find($id);

            if (!$template) {
                return $this->error('Notification Template Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->success($template, 'Template Fetched Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to fetch template', $e->getCode(), $e->getTrace());
        }
    }

    public function update(UpdateNotificationTemplateRequest $request, int $id): JsonResponse
    {
        try {
            $template = $this->repository->find($id);

            if (!$template) {
                return $this->error('Notification Template Not Found', Response::HTTP_NOT_FOUND);
            }

            $updated = $this->repository->update($template, $request->validated());

            return $this->success($updated ?? $template, 'Template Updated Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to update template', $e->getCode(), $e->getTrace());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $template = $this->repository->find($id);

            if (!$template) {
                return $this->error('Notification Template Not Found', Response::HTTP_NOT_FOUND);
            }

            $this->repository->delete($template);

            return $this->success([], 'Template Deleted Successfully');
        } catch (Exception $e) {
            return $this->error('Failed to delete template', $e->getCode(), $e->getTrace());
        }
    }
}
