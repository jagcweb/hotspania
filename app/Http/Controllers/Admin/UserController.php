<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Helpers\StorageHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
use App\Models\CityUser;

class UserController extends Controller
{
    /**
     * Display a form to create a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::orderBy('id', 'asc')->get();

        if(count($cities) == 0){
            return redirect()->route('admin.utilities.zones')->with('error', 'Antes de crear un usuario, debes tener alguna ciudad creada para poder asignarsela.');
        }

        return view('admin.users.create', compact("cities"));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'dni' => 'required|string|max:20', // Ajusta la longitud según tu país
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:users',
            'dni_file' => 'required|file|mimes:jpg,jpeg,png,webp',
            'nickname' => 'required|string|max:50|unique:users',
            'age' => 'required|numeric|min:18|max:99',
            'whatsapp' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'smoker' => 'required|boolean', // Assuming a boolean field for smoker
            'working_zone' => 'required|string',
            'service_location' => 'required|string',
            'gender' => 'required|in:hombre,mujer,otro',
            'height' => 'required|numeric', // Ajusta el rango según tus necesidades
            'weight' => 'required|numeric', // Ajusta el rango según tus necesidades
            'bust' => 'required|numeric',
            'waist' => 'required|numeric',
            'hip' => 'required|numeric',
            'start_day' => 'required|in:fulltime,lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'end_day' => 'required|in:fulltime,lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'start_time' => 'nullable|numeric|min:0|max:23',
            'end_time' => 'nullable|numeric|min:0|max:23',
            'fulltime_time' => 'nullable|numeric',
            'city' => 'required|array',
            'city.*' => 'alpha_num',
            'link' => 'nullable|regex:/^https:\/\//',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if(count($request->get('city')) == 0) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos una ciudad.');
        }

        if(is_null($request->get('start_time')) && is_null($request->get('end_time')) && is_null($request->get('fulltime_time'))) {
            return redirect()->back()->with('error', 'Debes seleccionar un horario.');
        }

        $file = $request->file('dni_file');

        $imageName = time() . $file->getClientOriginalName();
        \Storage::disk(StorageHelper::getDisk('images'))->put($imageName, \File::get($file));

        $create = User::create([
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'age' => $request->age,
            'whatsapp' => $request->whatsapp,
            'phone' => $request->phone,
            'is_smoker' => $request->smoker,
            'working_zone' => $request->working_zone,
            'service_location' => $request->service_location,
            'gender' => $request->gender,
            'dni' => $request->dni,
            'dni_file' => $imageName,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'height' => $request->height,
            'weight' => $request->weight,
            'bust' => $request->bust,
            'waist' => $request->waist,
            'hip' => $request->hip,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'start_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->start_time,
            'end_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->end_time,
            'status' => 0,
            'profile_image' => NULL,
            'active' => 1,
            'frozen' => NULL,
            'visible' => NULL,
            'online' => NULL,
            'email_verified_at' => NULL,
            'banned' => NULL,
            'password' => \Hash::make($request->dni),
            'link' => $request->link,
            'completed' => 1
        ]);

        $create->assignRole('user');

        foreach($request->get('city') as $city){
            $city_user = new CityUser();
            $city_user->city_id = $city;
            $city_user->user_id = $create->id;
            $city_user->save();
        }

        return redirect()->route('admin.users.getActive')->with('exito', 'Usuario creado');
    }

    public function edit($id) {
        $user = User::find($id);
        $cities = City::orderBy('id', 'asc')->get();
        return view('admin.users.edit', [
            'user' => $user,
            'cities' => $cities
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'dni' => 'required|string|max:20', // Ajusta la longitud según tu país
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:users,id',
            'dni_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'nickname' => 'required|string|max:50|unique:users,id',
            'age' => 'required|numeric|min:18|max:99',
            'whatsapp' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'smoker' => 'required|boolean', // Assuming a boolean field for smoker
            'working_zone' => 'required|string',
            'service_location' => 'required|string',
            'gender' => 'required|in:hombre,mujer,otro',
            'height' => 'required|numeric', // Ajusta el rango según tus necesidades
            'weight' => 'required|numeric', // Ajusta el rango según tus necesidades
            'bust' => 'required|numeric',
            'waist' => 'required|numeric',
            'hip' => 'required|numeric',
            'start_day' => 'required|in:fulltime,lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'end_day' => 'required|in:fulltime,lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'start_time' => 'nullable|numeric|min:0|max:23',
            'end_time' => 'nullable|numeric|min:0|max:23',
            'fulltime_time' => 'nullable|numeric',
            'user_id' => 'required|integer',
            'city' => 'required|array',
            'city.*' => 'alpha_num',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if(count($request->get('city')) == 0) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos una ciudad.');
        }

        if(is_null($request->get('start_time')) && is_null($request->get('end_time')) && is_null($request->get('fulltime_time'))) {
            return redirect()->back()->with('error', 'Debes seleccionar un horario.');
        }

        $id = $request->get('user_id');

        $user = User::find($id);

        if(!is_null($request->file('dni_file'))) {
            \Storage::disk(StorageHelper::getDisk('images'))->delete($user->dni_file);

            $file = $request->file('dni_file');

            $imageName = time() . $file->getClientOriginalName();
            \Storage::disk(StorageHelper::getDisk('images'))->put($imageName, \File::get($file));
        }

        $cities = \App\Models\CityUser::where('user_id', $user->id)->get();

        foreach($cities as $city){
            $city->delete();
        }

        $user->update([
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'age' => $request->age,
            'whatsapp' => $request->whatsapp,
            'phone' => $request->phone,
            'is_smoker' => $request->smoker,
            'working_zone' => $request->working_zone,
            'service_location' => $request->service_location,
            'gender' => $request->gender,
            'dni' => $request->dni,
            'dni_file' => !is_null($request->file('dni_file')) ? $imageName : $user->dni_file,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'height' => $request->height,
            'weight' => $request->weight,
            'bust' => $request->bust,
            'waist' => $request->waist,
            'hip' => $request->hip,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'start_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->start_time,
            'end_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->end_time,
            'link' => $request->link,
            'updated_at' => \Carbon\Carbon::now()
        ]);

        foreach($request->get('city') as $city){
            $city_user = new CityUser();
            $city_user->city_id = $city;
            $city_user->user_id = $user->id;
            $city_user->save();
        }

        return back()->with('exito', 'Usuario modificado!');
    }

    public function updateStatus(Request $request) {
        $id = $request->get('user_id');

        $user = User::find($id);

        $user->update([
            'active' => $request->active,
            'frozen' => $request->frozen,
            'visible' => $request->visible,
        ]);

        return back()->with('exito', 'Usuario actualizado!');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([  
            'password' => ['required', 'min:8'],
        ]);

        $user = User::find($id);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('exito', 'La contraseña se ha actualizado correctamente.');
    }

    /**
     * Get a list of pending users (e.g. not activated).
     *
     * @return \Illuminate\Http\Response
     */
    public function getPending()
    {
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name', 'user');
            })
            ->whereNull('active');

        if (request()->get('search')) {
            $users = $users->where('nickname', 'like', '%' . request()->get('search') . '%');
        }

        $users = $users->get();

        return view('admin.users.pending', compact('users'));
    }

    /**
     * Get a list of active users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActive()
    {
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name', 'user');
            })
            ->where('active', 1);

        if (request()->get('search')) {
            $users = $users->where('nickname', 'like', '%' . request()->get('search') . '%');
        }

        $users = $users->get();

        return view('admin.users.active', compact('users'));
    }

    public function getPositionals()
    {
        $baseQuery = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })
        ->whereNotNull('active')
        ->whereNotNull('completed')
        ->whereNull('banned')
        ->whereHas('packageUser', function ($q) {
            $q->where('end_date', '>=', now())
              ->orderBy('end_date', 'desc');
        })
        ->whereHas('images', function($q) {
            $q->where('frontimage', 1);
        });

        if (request()->get('search')) {
            $baseQuery->where('nickname', 'like', '%' . request()->get('search') . '%');
        }

        // Usuarios con posición asignada
        $orderedUsers = (clone $baseQuery)
            ->whereNotNull('position')
            ->orderBy('position')
            ->get();

        // Usuarios sin posición asignada
        $unorderedUsers = (clone $baseQuery)
            ->whereNull('position')
            ->get();

        return view('admin.users.positions', compact('orderedUsers', 'unorderedUsers'));
    }

    public function updatePositions(Request $request)
    {
        $positions = $request->get('positions');
        
        foreach ($positions as $position) {
            if (isset($position['id'])) {
                User::where('id', $position['id'])
                    ->update(['position' => $position['position']]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get a list of requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRequests()
    {
        $users = User::where('status', 1)->get(); // Replace 1 with your active user status value

        return view('admin.users.active', compact('users'));
    }

    /**
     * Get a list of user login records.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLoginRecords()
    {
        // You might need to implement login history functionality or use an existing package

        return view('admin.users.login-records');
    }

    public function makeAvailable(Request $request, $id) {
        try {
            $decryptedId = \Crypt::decryptString($id);
        } catch (\Exception $e) {
            return back()->with('error', 'ID inválido');
        }

        $validator = Validator::make($request->all(), [
            'tiempo' => 'required|integer|min:1|max:3'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Datos inválidos');
        }

        $user = User::find($decryptedId);
        
        if(!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $user->update([
            'available_time' => $request->tiempo,
            'available_until' => \Carbon\Carbon::now('Europe/Madrid')->addHours($request->tiempo)
        ]);

        return back()->with('exito', 'Usuario marcado como disponible por ' . $request->tiempo . ' horas');
    }
}
