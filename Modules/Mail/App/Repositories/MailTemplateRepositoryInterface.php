<?php

namespace Modules\Mail\App\Repositories;

use Modules\Mail\App\Models\MailTemplate;

interface MailTemplateRepositoryInterface
{
    public function paginate(int $perPage = 20);
    public function findById(int $id): ?MailTemplate;
    public function findByCode(string $code): ?MailTemplate;
    public function create(array $data): MailTemplate;
    public function update(MailTemplate $template, array $data): MailTemplate;
    public function delete(MailTemplate $template): bool;
}
