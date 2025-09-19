<?php

namespace Modules\Vote\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Modules\Congress\App\Models\Congress;
use Modules\Vote\App\Models\VoteSession;

class VoteSessionController extends Controller
{
    public function index()
    {
        $sessions = VoteSession::with('votes')->latest()->paginate(10);
        return view('vote::sessions.index', compact('sessions'));
    }

    public function create()
    {
        $congresses = Congress::all();
        return view('vote::sessions.create', compact('congresses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'congress_id' => 'required|exists:congresses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $session = VoteSession::create($data);

        return redirect()->route('vote.ui.sessions.index')
            ->with(['message' => 'Tạo phiên biểu quyết thành công', 'alert-type' => 'success']);
    }

    public function show($id)
    {
        $session = VoteSession::with('votes')->findOrFail($id);
        return view('vote::sessions.show', compact('session'));
    }

    public function edit($id)
    {
        $session = VoteSession::findOrFail($id);
        $congresses = Congress::all();

        return view('vote::sessions.edit', compact('session', 'congresses'));
    }

    public function update(Request $request, $id)
    {
//        dd($id);
        try {
            $request->validate([
                'congress_id' => 'required|exists:congresses,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'required_percentage' => 'required|numeric|min:0|max:100',
            ]);

            $session = VoteSession::findOrFail($id);
            $session->update($request->only([
                'congress_id',
                'title',
                'description',
                'required_percentage',
            ]));

            return redirect()
                ->route('vote.ui.sessions.index')
                ->with([
                    'message' => 'Cập nhật phiên biểu quyết thành công!',
                    'alert-type' => 'success'
                ]);

        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->with([
                    'message' => 'Không tìm thấy phiên biểu quyết với ID: ' . $id,
                    'alert-type' => 'error'
                ]);

        } catch (QueryException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage(),
                    'alert-type' => 'error'
                ]);

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                    'alert-type' => 'error'
                ]);
        }
    }

    public function listData(Request $request)
    {
        $congressId = $request->get('congress_id');

        if (!$congressId) {
            return response()->json([
                'status' => false,
                'message' => 'Thiếu tham số congress_id'
            ], 400);
        }

        $resolutions = VoteSession::where('congress_id', $congressId)
            ->orderBy('id')
            ->get(['id', 'title']);

        return response()->json([
            'status' => true,
            'data' => $resolutions,
        ]);
    }
}
