<?php

namespace Modules\Mail\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Mail\App\Http\Requests\MailConfigStoreRequest;
use Modules\Mail\App\Http\Requests\MailConfigUpdateRequest;
use Modules\Mail\App\Repositories\MailConfigRepositoryInterface;
use Modules\Mail\App\Services\MailConfigService;

class MailConfigController extends Controller
{
    public function __construct(
        private MailConfigRepositoryInterface $repo,
        private MailConfigService $cfgService
    ) {
        // TODO: middleware('auth'); // permission
    }

    public function index(Request $request) {
        $perPage = (int) ($request->get('per_page', 20));
        return response()->json($this->repo->paginate($perPage));
    }

    public function show(int $id) {
        $cfg = $this->repo->findById($id);
        abort_if(!$cfg, 404, 'Config not found');

        $arr = $cfg->toArray();
        unset($arr['password']);
        return response()->json($arr);
    }

    public function store(MailConfigStoreRequest $request) {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = $this->cfgService->encryptPassword($data['password']);
        }

        $cfg = DB::transaction(function () use ($data) {
            $makeActive = (bool)($data['is_active'] ?? false);
            unset($data['is_active']);

            $created = $this->repo->create($data);
            if ($makeActive) {
                $this->repo->deactivateAll();
                $created->is_active = true;
                $created->save();
            }
            return $created;
        });

        $arr = $cfg->toArray();
        unset($arr['password']);
        return response()->json($arr, 201);
    }

    public function update(int $id, MailConfigUpdateRequest $request) {
        $cfg = $this->repo->findById($id);
        abort_if(!$cfg, 404, 'Config not found');

        $data = $request->validated();

        if (array_key_exists('password', $data)) {
            $data['password'] = $data['password'] ? $this->cfgService->encryptPassword($data['password']) : $cfg->password;
        }

        $cfg = DB::transaction(function () use ($cfg, $data) {
            $makeActive = array_key_exists('is_active', $data) ? (bool)$data['is_active'] : null;
            unset($data['is_active']);

            $updated = $this->repo->update($cfg, $data);

            if ($makeActive === true) {
                $this->repo->deactivateAll();
                $updated->is_active = true;
                $updated->save();
            } elseif ($makeActive === false) {
                $updated->is_active = false;
                $updated->save();
            }

            return $updated;
        });

        $arr = $cfg->toArray();
        unset($arr['password']);
        return response()->json($arr);
    }

    public function destroy(int $id) {
        $cfg = $this->repo->findById($id);
        abort_if(!$cfg, 404, 'Config not found');
        $this->repo->delete($cfg);
        return response()->json(['success' => true]);
    }

    public function activate(int $id) {
        $cfg = $this->repo->findById($id);
        abort_if(!$cfg, 404, 'Config not found');

        DB::transaction(function () use ($cfg) {
            $this->repo->deactivateAll();
            $cfg->is_active = true;
            $cfg->save();
        });

        return response()->json(['success' => true]);
    }
}
