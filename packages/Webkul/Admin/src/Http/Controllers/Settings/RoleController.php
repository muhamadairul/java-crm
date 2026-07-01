<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Settings\RoleDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected RoleRepository $roleRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(RoleDataGrid::class)->process();
        }

        return view('admin::settings.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->guard('user')->user();
        if ($user->company_id !== null) {
            abort(403, 'Tenant admin tidak diperbolehkan membuat role baru.');
        }

        return view('admin::settings.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $user = auth()->guard('user')->user();
        if ($user->company_id !== null) {
            abort(403, 'Tenant admin tidak diperbolehkan membuat role baru.');
        }

        $this->validate(request(), [
            'name'            => 'required',
            'permission_type' => 'required|in:all,custom',
            'description'     => 'required',
        ]);

        if (request('permission_type') == 'custom') {
            $this->validate(request(), [
                'permissions' => 'required',
            ]);
        }

        Event::dispatch('settings.role.create.before');

        $data = request()->only([
            'name',
            'description',
            'permission_type',
            'permissions',
        ]);

        // Automatically scope to current user's company
        $user = auth()->guard('user')->user();
        if ($user->company_id) {
            $data['company_id'] = $user->company_id;
        }

        $role = $this->roleRepository->create($data);

        Event::dispatch('settings.role.create.after', $role);

        session()->flash('success', trans('admin::app.settings.roles.index.create-success'));

        return redirect()->route('admin.settings.roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $role = $this->roleRepository->findOrFail($id);

        // Ensure tenant users can only edit roles from their own company
        $user = auth()->guard('user')->user();
        if ($user->company_id && $role->company_id !== $user->company_id) {
            abort(403, 'Anda tidak memiliki akses ke role ini.');
        }

        return view('admin::settings.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): RedirectResponse
    {
        $role = $this->roleRepository->findOrFail($id);

        // Ensure tenant users can only update roles from their own company
        $user = auth()->guard('user')->user();
        if ($user->company_id && $role->company_id !== $user->company_id) {
            abort(403, 'Anda tidak memiliki akses ke role ini.');
        }

        // For default roles (Company Admin, Sales User), only allow editing permissions
        if ($role->isDefault()) {
            $this->validate(request(), [
                'permission_type' => 'required|in:all,custom',
                'permissions'     => 'required_if:permission_type,custom',
            ]);

            $data = [
                'permission_type' => request('permission_type'),
                'permissions'     => request()->has('permissions') ? request('permissions') : [],
            ];
        } else {
            $this->validate(request(), [
                'name'            => 'required',
                'permission_type' => 'required|in:all,custom',
                'description'     => 'required',
                'permissions'     => 'required_if:permission_type,custom',
            ]);

            $data = array_merge(request()->only([
                'name',
                'description',
                'permission_type',
            ]), [
                'permissions' => request()->has('permissions') ? request('permissions') : [],
            ]);
        }

        Event::dispatch('settings.role.update.before', $id);

        $role = $this->roleRepository->update($data, $id);

        Event::dispatch('settings.role.update.after', $role);

        session()->flash('success', trans('admin::app.settings.roles.index.update-success'));

        return redirect()->route('admin.settings.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $response = [
            'responseCode' => 400,
        ];

        $role = $this->roleRepository->findOrFail($id);

        // Ensure tenant users cannot delete roles
        $user = auth()->guard('user')->user();
        if ($user->company_id !== null) {
            return response()->json(['responseCode' => 403, 'message' => 'Tenant admin tidak diperbolehkan menghapus role.'], 403);
        }

        if ($role->users && $role->users->count() >= 1) {
            $response['message'] = trans('admin::app.settings.roles.index.being-used');

            session()->flash('error', $response['message']);
        } elseif ($this->roleRepository->where('company_id', $user->company_id)->count() <= 2) {
            $response['message'] = 'Minimal harus ada 2 role dalam perusahaan.';

            session()->flash('error', $response['message']);
        } else {
            try {
                Event::dispatch('settings.role.delete.before', $id);

                if (auth()->guard('user')->user()->role_id == $id) {
                    $response['message'] = trans('admin::app.settings.roles.index.current-role-delete-error');
                } else {
                    $this->roleRepository->delete($id);

                    Event::dispatch('settings.role.delete.after', $id);

                    $message = trans('admin::app.settings.roles.index.delete-success');

                    $response = [
                        'responseCode' => 200,
                        'message'      => $message,
                    ];

                    session()->flash('success', $message);
                }
            } catch (\Exception $exception) {
                $message = $exception->getMessage();

                $response['message'] = $message;

                session()->flash('error', $message);
            }
        }

        return response()->json($response, $response['responseCode']);
    }
}
