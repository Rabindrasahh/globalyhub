<?php

namespace App\Repositories\Interfaces;

use App\Models\NotificationTemplate;

interface NotificationTemplateRepositoryInterface
{
    public function all();

    public function find(int $id): ?NotificationTemplate;

    public function create(array $data): NotificationTemplate;

    public function update(NotificationTemplate $template, array $data): bool;

    public function delete(NotificationTemplate $template): bool;
}
