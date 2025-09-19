<?php

namespace Modules\Mail\App\Repositories;

use Modules\Mail\App\Models\MailTemplate;

class MailTemplateRepository implements MailTemplateRepositoryInterface
{
    public function paginate(int $perPage = 20)
    {
        return MailTemplate::query()->orderByDesc('id')->paginate($perPage);
    }

    public function findById(int $id): ?MailTemplate
    {
        return MailTemplate::find($id);
    }

    public function findByCode(string $code): ?MailTemplate
    {
        return MailTemplate::where('code', $code)->first();
    }

    public function create(array $data): MailTemplate
    {
        return MailTemplate::create($data);
    }

    public function update(MailTemplate $template, array $data): MailTemplate
    {
        $template->update($data);
        return $template;
    }

    public function delete(MailTemplate $template): bool
    {
        return (bool)$template->delete();
    }

    public function getAllTemplates()
    {
        return MailTemplate::all();
    }
}
