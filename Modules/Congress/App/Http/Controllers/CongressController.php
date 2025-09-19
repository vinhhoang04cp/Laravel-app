<?php

namespace Modules\Congress\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Congress\App\Requests\StoreCongressRequest;
use Modules\Congress\App\Requests\UpdateCongressRequest;
use Illuminate\Http\Request;
use Modules\Congress\App\Services\CongressService;
use Modules\Shareholder\App\Enums\RegistrationStatus;

class CongressController extends Controller
{
    public function __construct(protected CongressService $congressService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('congress::index');
    }

    public function list()
    {
        return $this->congressService->getListCongresses();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('congress::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCongressRequest $request): RedirectResponse
    {
        //
        $this->congressService->createCongress($request->validated());

        return redirect()->route('congresses.index')
            ->with('toastr', ['type' => 'success', 'message' => 'Congress created successfully']);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('congress::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $congress = $this->congressService->getCongressById($id);

        return view('congress::edit', compact('congress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCongressRequest $request, $id): RedirectResponse
    {
        //
        $this->congressService->updateCongress($id, $request->validated());

        return redirect()->route('congresses.index')
            ->with('toastr', ['type' => 'success', 'message' => 'Congress updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $this->congressService->deleteCongress($id);

        return redirect()->route('congresses.index')->with('success', 'Congress deleted successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
            'congress_id' => 'required|exists:congresses,id',
        ]);

        try {
            $result = $this->congressService->importFromExcel(
                $request->file('excel_file'),
                $request->get('congress_id')
            );

            return redirect()->route('congresses.index')
                ->with('toastr', [
                    'type' => 'success',
                    'message' => $result['message'] ?? 'Dữ liệu đang được xử lý qua hàng đợi.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('congresses.index')
                ->with('toastr', [
                    'type' => 'error',
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function shareholders($congressId)
    {
        $statuses = RegistrationStatus::values();
        return view('congress::shareholders', compact('congressId', 'statuses'));
    }

    public function shareholderList($congressId)
    {
        return $this->congressService->getShareholdersByCongressId($congressId);
    }

    public function removeShareholder(Request $request)
    {
        $shareholder_id = (int)$request->input('shareholder_id');
        $congress_id = (int)$request->input('congress_id');

        $result = $this->congressService->removeShareholderFromCongress($shareholder_id, $congress_id);

        return response()->json($result);
    }
}
