<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Services\ServicesFromKeyword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class BLDGApprovalController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 'pending_building_inspection')->paginate(20);
        return view('pages.bldg-request-approval', ['search_route' => 'admin.search','route' => 'admin.undo', 'text' => ['Lists of Request for Building/Area Inspections', 'View Approved Request'], 'search_heading' => 'SEARCH REQUEST','services' => $services]);
    }

    public function search(Request $request){
        $services = (new ServicesFromKeyword)->get($request->account_number, 'pending_building_inspection');
        $services = new Paginator($services->all(), 10);

        // dd($services);
        return view('pages.bldg-request-approval', ['search_route' => 'admin.search','route' => 'admin.request-approvals', 'text' => ['Lists of Request for Building/Area Inspections', 'Return Back'], 'search_heading' => 'SEARCH REQUEST','services' => $services]);
    }

    public function search_denied(Request $request){
        $services = (new ServicesFromKeyword)->get($request->account_number, 'denied_building_inspection');
        $services = new Paginator($services->all(), 10);

        // dd($services);
        return view('pages.bldg-request-approval', ['search_route' => 'admin.search.denied','route' => 'admin.request-approvals', 'text' => ['Lists of Denied Request for Building/Area Inspections', 'Return Back'], 'search_heading' => 'SEARCH REQUEST','services' => $services]);
    }

    public function approve(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->status = "pending_waterworks_inspection";
        $service->save();

        return redirect(route('admin.request-approvals'));
    }

    public function reject($id)
    {
        $service = Service::findOrFail($id);
        $service->status = "denied_building_inspection";
        $service->save();

        return redirect(route('admin.request-approvals'));
    }

    public function undo()
    {
        $services = Service::where('status', 'denied_building_inspection')->paginate(20);
        return view('pages.bldg-request-approval', ['search_route' => 'admin.search.denied','route' => 'admin.request-approvals', 'text' => ['Lists of Denied Request for Building/Area Inspections', 'Return Back'], 'search_heading' => 'SEARCH REQUEST','services' => $services]);
    }


    public function undoStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->status = "pending_building_inspection";
        $service->save();

        return redirect(route('admin.request-approvals'));
    }
}
