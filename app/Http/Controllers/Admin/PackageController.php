<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Http\Controllers\Controller;
use App\Http\Requests\Package\AddPackageRequest;
use App\Http\Requests\Package\UpdateStatusRequest;

class PackageController extends Controller
{
    public function getPackages()
    {
        $search = request()->query('search');
        $packages = Package::when($search, fn ($q) => $q->search($search))->latest()->paginate(20);

        return sendSuccess($packages, 'Packages fetched successfully');
    }

    public function getPackage($id)
    {
        $package = Package::find($id);
        return sendSuccess($package, 'Package fetched successfully');
    }

    public function createPackage(AddPackageRequest $request)
    {

        try {
            $package = Package::create([
                'name' => $request->name,
                'inapp_package_id' => $request->inapp_package_id ?? null,
                'inapp_android_package' => $request->inapp_android_package,
                'price' => $request->price,
                'description' => $request->description,
                'is_active' => $request->is_active === true ? 1 : 0,
            ]);

            return sendSuccess($package, 'Package created successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse("Something went wrong, please try again later." . $th->getMessage());
        }
    }

    public function updatePackage(AddPackageRequest $request)
    {
        try {
            $package = Package::find($request->package_id);
            $package->update([
                'name' => $request->name,
                'inapp_package_id' => $request->inapp_package_id ?? $package->inapp_package_id,
                'inapp_android_package' => $request->inapp_android_package ?? $package->inapp_android_package,
                'price' => $request->price,
                'description' => $request->description,
                'is_active' => $request->is_active === true ? 1 : 0,
            ]);

            return sendSuccess($package, 'Package updated successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse("Something went wrong, please try again later." . $th->getMessage());
        }
    }


    public function updateStatus(UpdateStatusRequest $request)
    {
        $package = Package::find($request->id);
        if (!$package) {
            return sendErrorResponse('Package not found');
        }
        $package->update(['is_active' => $request->is_active]);

        return sendSuccess($package, 'Package status updated successfully');
    }

    public function delete($id)
    {
        $package = Package::find($id);
        if (!$package) {
            return sendErrorResponse('Package not found');
        }
        $package->delete();

        return sendSuccess(null, 'Package deleted successfully');
    }
}
