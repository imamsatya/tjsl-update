<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'role';
        $this->pagetitle = 'Role';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'User Management - Role'
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        try{
            return datatables()->of(Role::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->name.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->name.'" data-toggle="tooltip" title="Hapus data '.$row->name.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','action'])
            ->toJson();
        }catch(Exception $e){
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $permission = Permission::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'permission' => $permission
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param['name'] = $request->input('name');
            $param['keterangan'] = $request->input('keterangan');
            $permission = $request->input('permission');
            $menu = explode(',', $request->input('menu'));

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $param['guard_name'] = 'web';
                                  $role = Role::create((array)$param);
                                  $role->syncPermissions($request->input('permission'));
                                  $role->menus()->sync($menu);

                                  DB::commit();
                                  $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses tambah data',
                                    'title' => 'Sukses'
                                  ];
                               }catch(\Exception $e){
                                  DB::rollback();
                                  $result = [
                                    'flag'  => 'warning',
                                    'msg' => $e->getMessage(),
                                    'title' => 'Gagal'
                                  ];
                               }

                break;

                case 'update': DB::beginTransaction();
                               try{
                                  $role = Role::find((int)$request->input('id'));
                                  $role->syncPermissions($request->input('permission'));
                                  $role->update((array)$param);
                                  $role->menus()->sync($menu);

                                  DB::commit();
                                  $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses ubah data',
                                    'title' => 'Sukses'
                                  ];
                               }catch(\Exception $e){
                                  DB::rollback();
                                  $result = [
                                    'flag'  => 'warning',
                                    'msg' => $e->getMessage(),
                                    'title' => 'Gagal'
                                  ];
                               }

                break;
            }
        }else{
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag'  => 'warning',
                'msg' => '<ul>'.implode('', $messages).'</ul>',
                'title' => 'Gagal proses data'
            ];
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try{

            $role = Role::find((int)$request->input('id'));
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",(int)$request->input('id'))
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'permission' => $permission,
                    'rolePermissions' => $rolePermissions,
                    'role' => $role

                ]);
        }catch(Exception $e){}

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Role::find((int)$request->input('id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['name'] = 'required';

        $message['name.required'] = 'Nama Role wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
    
    public function gettreemenubyrole($id=null)
    {
      try{
        $result = $this->getarrayrolebymenu((int)$id);
        return response()->json($result);
      }catch(Exception $e){
        return response()->json([]);
      }
    }

    private function getarrayrolebymenu($id)
    {
      $data = Menu::where('status',true)->orderBy('order')->get();
      $menurole = [];
      if((bool)$id){
        //jika id ada artinya ini bagian edit lakukan pengambilan data referensi
        $row = Role::find($id);
        $menurole = $row->menus()->get()->pluck('id')->toArray();
      }
      return $this->recursivemenu($data, 0, $menurole);
    }

    private function recursivemenu($data, $parent_id, $menurole)
    {
      $array = [];
        $result = $data->where('parent_id', (int)$parent_id)->sortBy('order');
        foreach ($result as $val) {
          $child = $data->where('parent_id', (int)$val->id)->sortBy('order');

          $array[] = [
            'id' => (int)$val->id,
            'text' => $val->label,
            'state' => [
              'opened' => (bool)$child->count()? true : false,
              'selected' => $val->id == 1? true : ((bool)count($menurole)? (in_array($val->id, $menurole)? true : false) : false),
              'disabled' => $val->id == 1? true : false
            ],
            'children' => (bool)$child->count()? $this->recursivemenu($data, (int)$val->id, $menurole) : []
          ];
        }
        return $array;    
    }
}
