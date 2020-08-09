<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Datatables\AdminsDataTable;
use Modules\Admin\Http\Controllers\Controller as CoreController;
use Modules\Admin\Entities\Admin;
use DataTables;
use phpDocumentor\Reflection\Types\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Admin\Http\Requests\AdminCreateRequest;
use Modules\Admin\Http\Requests\AdminUpdateRequest;
use Modules\Core\src\FormBuilder\Traits\FormBuilderTrait;

class AdminController extends CoreController
{
    use FormBuilderTrait;

    protected $model;

    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    protected function redirectNotFoundModel()
    {
        return $this->redirect('admins.' . $this->defaultRedirect);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(AdminsDataTable $dataTable)
    {
        return $dataTable->render('admin::panel.admins.index');
//        if ($request->ajax()) {1
//            $data = Admin::latest()->get();
//            return Datatables::of($data)
//                ->addIndexColumn()
//                ->addColumn('action', function ($row) {
//                    $btn = '<div class="flex align-items-center justify-content-center flex-wrap">
//                           <a href="' . route('admin.admins.show', $row->id) . '" class="btn-action-table"><i class="far fa-eye"></i></a>
//                           <a href="' . route('admin.admins.edit', $row->id) . '" class="btn-action-table"><i class="fas fa-pencil-alt"></i></a>
//                       </div>';
//                    return $btn;
//                })
//                ->editColumn('role_id', function ($data) {
//                    return ($data->role_id) ? $data->role->name : 'brak';
//                })
//                ->rawColumns(['action'])
//                ->make(true);
//        }

//        return $this->view('panel.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $form = $this->form(\Modules\Admin\Forms\AdminForm::class, [
            'method' => 'POST',
            'route' => ['admin.admins.store']
        ]);

        return $this->view('panel.admins.create', ['form' => $form]);
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminCreateRequest $request
     * @return Response
     */
    public function store(AdminCreateRequest $request)
    {
        $user = $this->model->create($request->all());
        if ($user) {
            return $this->redirect('admins.index');
        }
    }

    /**
     * Show the specified resource.
     * @param Collection $admin
     * @return Response
     */
    public function show($id)
    {
        try {
            $admin = $this->adminRepository->findById($id);
            return view('admin::panel.admins.show', ['item' => $admin]);
        } catch (ModelNotFoundException $e) {
            dd($e);
            return $this->redirectNotFound();
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @param Collection $admin
     * @return Response
     */
    public function edit(Admin $admin)
    {
        unset($admin->password);
        $form = $this->form(\Modules\Admin\Forms\AdminForm::class, [
            'method' => 'POST',
            'route' => ['admin.admins.update', $admin->id],
            'model' => $admin
        ]);

        return $this->view('panel.admins.edit', [
            'form' => $form,
            'item' => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param AdminCreateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        if ($request->has('password') && $request->password !== '') {
            $user = $admin->update($request->all());
        } else {
            $user = $admin->update($request->except('password'));
        }

        if ($user) {
            return $this->redirect('admins.edit', $admin->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
