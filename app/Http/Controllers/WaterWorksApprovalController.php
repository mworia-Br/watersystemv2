<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Services\ServicesFromKeyword;
use Illuminate\Pagination\Paginator;

class WaterWorksApprovalController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'pending_waterworks_inspection')->paginate(20);
        return view('pages.waterworks-request-approval', ['search_route' => 'admin.water.search','route' => 'admin.waterworks-request-approvals', 'search_heading' => 'SEARCH REQUEST', 'services' => $services]);
    }

    public function search(Request $request){
        $services = (new ServicesFromKeyword)->get($request->account_number, 'pending_waterworks_inspection');
        $services = new Paginator($services->all(), 10);

        // dd($services);
        return view('pages.waterworks-request-approval', ['search_route' => 'admin.water.search','route' => 'admin.waterworks-request-approvals','services' => $services]);
    }

    // public function search_denied(Request $request){
    //     $services = (new ServicesFromKeyword)->get($request->account_number, 'denied_waterworks_inspection');
    //     $services = new Paginator($services->all(), 10);

    //     // dd($services);
    //     return view('pages.waterworks-request-approval', ['search_route' => 'admin.water.search.denied','route' => 'admin.waterworks-request-approvals', 'services' => $services]);
    // }

    public function approve(Request $request)
    {
        $services = Service::findOrFail($request->id);
        $services->status = "waterworks_approved";
        $services->save();

        return redirect(route('admin.waterworks-request-approvals'));
    }

    public function reject($id)
    {
        $services = Service::findOrFail($id);
        $services->status = "denied_waterworks_inspection";
        $services->save();

        return redirect(route('admin.waterworks-request-approvals'));
    }
}
