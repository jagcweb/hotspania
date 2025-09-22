<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\City;
use App\Models\Tag;
use App\Models\Package;
use App\Models\PackageUser;
use App\Models\PackageUserHistory;
use App\Models\User;
use App\Models\Report;

class UtilityController extends Controller
{
    /**
     * Manage zones (e.g., locations, areas).
     *
     * @return \Illuminate\Http\Response
     */


    public function assignPackage(Request $request)
    {
        $user_id = $request->get('user_id');
        $package = Package::findOrFail($request->get('package_id'));
        
        // Obtener el último paquete del usuario
        $lastPackage = PackageUser::where('user_id', $user_id)
            ->orderBy('end_date', 'desc')
            ->first();

        $startDate = null;
        if ($lastPackage && $lastPackage->end_date > now()) {
            // Si hay un paquete activo, el nuevo empezará cuando termine el último
            $startDate = $lastPackage->end_date;
        } else {
            // Si no hay paquetes activos, empieza hoy
            $startDate = now()->startOfDay();
        }

        $pack_user = new PackageUser();
        $pack_user->package_id = $package->id;
        $pack_user->user_id = $user_id;
        $pack_user->start_date = $startDate;
        $pack_user->end_date = $startDate->copy()->addDays($package->days);
        $pack_user->save();

        // Guardar en historial
        $pack_history = new PackageUserHistory();
        $pack_history->package_id = $package->id;
        $pack_history->user_id = $user_id;
        $pack_history->save();

        return back()->with('exito', 'Paquete asignado.');
    }

    public function zones()
    {
        $cities = City::orderBy('name', 'asc')->get();

        $zones = Zone::orderBy('name', 'asc')->get();
        
        return view('admin.utilities.zones', [
            'zones' => $zones,
            'cities' => $cities,
        ]);
    }

    public function reports(Request $request)
    {
        $reports = Report::orderBy('created_at', 'desc')->get();

        return view('admin.utilities.reports', [
            'reports' => $reports,
        ]);
    }

    public function saveCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        
        $city = new City();
        $city->name = strtolower($request->input('name'));
        $city->save();

        return redirect()->back()->with('exito', 'Ciudad creada correctamente.');
    }

    public function deleteCity($id)
    {
        $city = City::find($id);

        $zones = Zone::where('city_id', $id)->get();

        foreach($zones as $zone) {
            $zone->delete();
        }

        $city->delete();

        return redirect()->back()->with('exito', 'Ciudad y zonas asociadas eliminadas correctamente.');
    }

    public function updateCity(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_num',
            'name' => 'required|string',
        ]);

        $id = $request->input('id');
        $city = City::find($id);
        $city->name = $request->input('name');
        $city->update();

        return redirect()->back()->with('exito', 'Ciudad actualizada correctamente.');
    }

    public function saveZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'city' => 'required|alpha_num',
        ]);
        
        $zone = new Zone();
        $zone->name = $request->input('name');
        $zone->city_id = $request->input('city');
        $zone->save();

        return redirect()->back()->with('exito', 'Zona creada correctamente.');
    }

    public function deleteZone($id)
    {
        $zone = Zone::find($id);
        $zone->delete();

        return redirect()->back()->with('exito', 'Zona eliminada correctamente.');
    }

    public function updateZone(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_num',
            'name' => 'required|string',
            'city' => 'required|alpha_num',
        ]);

        $id = $request->input('id');
        $zone = Zone::find($id);
        $zone->name = $request->input('name');
        $zone->city_id = $request->input('city');
        $zone->update();

        return redirect()->back()->with('exito', 'Zona actualizada correctamente.');
    }


    /**
     * Manage tags (e.g., categories, labels).
     *
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        $tags = Tag::orderBy('id', 'asc')->get();
        return view('admin.utilities.tags', [
            'tags' => $tags,
        ]);
    }

    public function chats(Request $request)
    {
        $files = \Storage::disk('chatbot_chats')->files(); // Lista de archivos en el disk

        // Si quieres ordenarlos por fecha descendente:
        usort($files, function ($a, $b) {
            return strcmp($b, $a);
        });

        return view('admin.utilities.chats', compact('files'));
    }

    public function saveTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->save();

        return redirect()->back()->with('exito', 'Tag creado correctamente.');
    }

    public function deleteTag($id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        return redirect()->back()->with('exito', 'Tag eliminado correctamente.');
    }

    public function updateTag(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_num',
            'name' => 'required|string',
        ]);
        
        $id = $request->input('id');
        $tag = Tag::find($id);
        $tag->name = $request->input('name');
        $tag->update();

        return redirect()->back()->with('exito', 'Tag actualizado correctamente.');
    }


    /**
     * Manage packages (e.g., plans, subscriptions).
     *
     * @return \Illuminate\Http\Response
     */
    public function packages()
    {
        $packages = Package::orderBy('id', 'asc')->get();

        $packages_actives = Package::where('status', 'activo')->orderBy('id', 'asc')->get();

        $users = User::where('active', 1)->orderBy('nickname', 'asc')->get();

        $packages_users = PackageUser::orderBy('id', 'asc')->get();

        return view('admin.utilities.packages', [
            'packages' => $packages,
            'packages_actives' => $packages_actives,
            'users' => $users,
            'packages_users' => $packages_users,
        ]);
    }

    public function savePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'days' => 'required|integer',
            'status' => 'required|string',
        ]);

        $package = new Package();
        $package->name = $request->input('name');
        $package->type = $request->input('type');
        $package->price = $request->input('price');
        $package->days = $request->input('days');
        $package->status = $request->input('status');
        $package->save();

        return redirect()->back()->with('exito', 'Paquete creado correctamente.');
    }

    public function deletePackage($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return redirect()->back()->with('error', 'Paquete no encontrado.');
        }

        $package->delete();

        return redirect()->back()->with('exito', 'Paquete eliminado correctamente.');
    }

    public function updatePackage(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_num',
            'name' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'days' => 'required|integer',
            'status' => 'required|string',
        ]);

        $id = $request->input('id');
        $package = Package::find($id);

        $package->name = $request->input('name');
        $package->type = $request->input('type');
        $package->price = $request->input('price');
        $package->days = $request->input('days');
        $package->status = $request->input('status');
        $package->save();

        return redirect()->back()->with('exito', 'Paquete actualizado correctamente.');
    }

    public function savePackageUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|alpha_num',
            'package_id' => 'required|alpha_num',
        ]);

        $package_user = new PackageUser();
        $package_user->user_id = $request->input('user_id');
        $package_user->package_id = $request->input('package_id');

        $package_user->save();

        return redirect()->back()->with('exito', 'Paquete asignado a usuario correctamente.');
    }

    public function deletePackageUser($id)
    {
        $package_user = PackageUser::find($id);

        if (!$package_user) {
            return redirect()->back()->with('error', 'Paquete asignado a usuario no encontrado.');
        }

        $package_user->delete();

        return redirect()->back()->with('exito', 'Paquete desasignado a usuario correctamente.');
    }


    /**
     * Manage news and announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function news(Request $request)
    {
        // Implement logic to manage news (e.g., listing, creating, editing, deleting)
        return view('admin.utilities.news');
    }

    public function downloadTranscript($filename)
    {
        // Validar que el archivo exista
        if (!\Storage::disk('chatbot_chats')->exists($filename)) {
            abort(404);
        }

        // Retornar el archivo como response para descargar
        return response()->streamDownload(function() use ($filename) {
            echo \Storage::disk('chatbot_chats')->get($filename);
        }, $filename);
    }

    public function viewTranscript($filename)
    {
        // Validar que el archivo exista
        if (!\Storage::disk('chatbot_chats')->exists($filename)) {
            abort(404);
        }

        $content = \Storage::disk('chatbot_chats')->get($filename);

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
