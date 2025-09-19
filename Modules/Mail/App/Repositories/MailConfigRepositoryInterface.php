<?php

namespace Modules\Mail\App\Repositories;

use Modules\Mail\App\Models\MailConfig;

interface MailConfigRepositoryInterface
{
    public function paginate(int $perPage = 20);
    public function findById(int $id): ?MailConfig;
    public function create(array $data): MailConfig;
    public function update(MailConfig $config, array $data): MailConfig;
    public function delete(MailConfig $config): bool;
    public function deactivateAll(): void;
    public function getActive(): ?MailConfig;
}
