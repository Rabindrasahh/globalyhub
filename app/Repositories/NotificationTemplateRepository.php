<?php

namespace App\Repositories;

use App\Models\NotificationTemplate;
use App\Repositories\Interfaces\NotificationTemplateRepositoryInterface;

class NotificationTemplateRepository implements NotificationTemplateRepositoryInterface
{
    public function all()
    {
        return NotificationTemplate::latest()->paginate(10);
    }

    public function find(int $id): ?NotificationTemplate
    {
        return NotificationTemplate::find($id);
    }

    public function create(array $data): NotificationTemplate
    {
        return NotificationTemplate::create($data);
    }

    public function update(NotificationTemplate $template, array $data): bool
    {
        return $template->update($data);
    }

    public function delete(NotificationTemplate $template): bool
    {
        return $template->delete();
    }
}
