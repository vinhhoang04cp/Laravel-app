<?php

namespace Modules\Mail\App\Repositories;

use Modules\Mail\App\Models\MailConfig;

class MailConfigRepository implements MailConfigRepositoryInterface
{
    public function paginate(int $perPage = 20) {
        return MailConfig::query()->orderByDesc('is_active')->orderBy('name')->paginate($perPage);
    }

    public function findById(int $id): ?MailConfig {
        return MailConfig::find($id);
    }

    public function create(array $data): MailConfig {
        return MailConfig::create($data);
    }

    public function update(MailConfig $config, array $data): MailConfig {
        $config->update($data);
        return $config;
    }

    public function delete(MailConfig $config): bool {
        return (bool) $config->delete();
    }

    public function deactivateAll(): void {
        MailConfig::query()->update(['is_active' => false]);
    }

    public function getActive(): ?MailConfig {
        return MailConfig::where('is_active', true)->first();
    }
}
