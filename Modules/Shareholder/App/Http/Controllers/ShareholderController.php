<?php

namespace Modules\Shareholder\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Shareholder\App\Requests\StoreShareholderRequest;
use Modules\Shareholder\App\Requests\UpdateShareholderRequest;
use Modules\Shareholder\App\Services\ShareholderService;

class ShareholderController extends Controller
{
    public function __construct(protected ShareholderService $shareholderService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shareholder::index');
    }

    public function list()
    {
        return $this->shareholderService->getDataTableShareholders();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shareholder::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShareholderRequest $request)
    {
       $shareholder = $this->shareholderService->createShareholder($request->validated());
       return response()->json($shareholder);
//        return redirect()->route('shareholders.index')->with('success', 'Shareholder created successfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('shareholder::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shareholder = $this->shareholderService->getShareholderById($id);
        return response()->json($shareholder);
//        return view('shareholder::edit', compact('shareholder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShareholderRequest $request, $id)
    {
        //
        $this->shareholderService->updateShareholder($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Shareholder updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function detail($shareholderId)
    {
        return $this->shareholderService->getShareholderById($shareholderId);
    }

    public function add(Request $request)
    {
        $this->shareholderService->createShareholder($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Shareholder updated successfully'
        ]);
    }

    public function invite(Request $request){
        $ids = $request->input('shareholder_ids', []);

        if (empty($ids)) {
            return response()->json(['error' => 'Không có cổ đông nào được chọn'], 400);
        }

        $shareholders = Shareholder::whereIn('id', $ids)->get();

        foreach ($shareholders as $shareholder) {
            dispatch(new \App\Jobs\SendShareholderInviteJob($shareholder));
        }

        return response()->json([
            'success' => 'Đã đưa vào hàng chờ gửi mail cho ' . $shareholders->count() . ' cổ đông'
        ]);
    }
}
