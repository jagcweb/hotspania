<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\StorageHelper;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['getImage']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereHas('packageUser', function ($q) {
                $q->whereHas('package', function ($query) {
                    $query->whereRaw("DATE_ADD(package_users.created_at, INTERVAL packages.days DAY) >= ?", [Carbon::today()->toDateString()]);
                });
            })
            ->with(['images', 'packageUser.package'])
            ->inRandomOrder()
            ->paginate(20);

        return view('home', compact('users'));
    }

    public function loadMore($page)
    {
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $loadedUsers = request()->input('loaded_users', []);
        
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereHas('packageUser', function ($q) {
                $q->whereHas('package', function ($query) {
                    $query->whereRaw("DATE_ADD(package_users.created_at, INTERVAL packages.days DAY) >= ?", [Carbon::today()->toDateString()]);
                });
            })
            ->when(!empty($loadedUsers), function($query) use ($loadedUsers) {
                return $query->whereNotIn('id', $loadedUsers);
            })
            ->with(['images', 'packageUser.package'])
            ->inRandomOrder()
            ->take($perPage)
            ->get();

        $totalUsers = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereHas('packageUser', function ($q) {
                $q->whereHas('package', function ($query) {
                    $query->whereRaw("DATE_ADD(package_users.created_at, INTERVAL packages.days DAY) >= ?", [Carbon::today()->toDateString()]);
                });
            })
            ->when(!empty($loadedUsers), function($query) use ($loadedUsers) {
                return $query->whereNotIn('id', $loadedUsers);
            })
            ->count();

        $hasMore = ($offset + $perPage) < $totalUsers;

        $html = view('partials.user-grid', ['users' => $users])->render();
        
        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'debug' => [
                'page' => $page,
                'offset' => $offset,
                'count' => $users->count(),
                'total' => $totalUsers
            ]
        ]);
    }

    public function privacyPolicies()
    {
        return view('privacy_policies');
    }

    public function getImage($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($filename);

        return new Response($file, 200);
    }

    public function getFrontImage($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($filename);

        return new Response($file, 200);
    }

    public function getGif($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('videogif'))->get($filename);

        return new Response($file, 200);
    }
}
