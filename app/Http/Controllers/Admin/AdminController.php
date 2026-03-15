<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')->latest()->paginate(10);
        return view('admin.pages.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.pages.admins.create', compact('roles'));
    }

    public function store(StoreAdminRequest $request)
    {
        DB::transaction(function () use ($request) {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);

            $admin->assignRole($request->role);
        });

        return redirect()->route('admin.admins')->with('success', 'تم إضافة المشرف بنجاح.');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.pages.admins.edit', compact('admin', 'roles'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        // Protection: Prevent deactivating the last Super Admin
        if ($request->status === 'inactive' && $admin->hasRole('Super Admin')) {
            $superAdminsCount = Admin::role('Super Admin')->where('status', 'active')->count();
            if ($superAdminsCount <= 1) {
                return redirect()->back()->with('error', 'لا يمكن تعطيل آخر مشرف بصلاحية Super Admin.');
            }
        }

        // Protection: Prevent admin from deactivating themselves
        if ($request->status === 'inactive' && auth('admin')->id() === $admin->id) {
            return redirect()->back()->with('error', 'لا يمكنك تعطيل حسابك الشخصي.');
        }

        DB::transaction(function () use ($request, $admin) {
            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
            ]);

            if ($request->filled('password')) {
                $admin->update(['password' => Hash::make($request->password)]);
            }

            $admin->syncRoles([$request->role]);
        });

        return redirect()->route('admin.admins')->with('success', 'تم تحديث بيانات المشرف بنجاح.');
    }
}
