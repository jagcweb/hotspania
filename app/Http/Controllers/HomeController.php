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
        // Primero obtenemos usuarios con posición
        $usersWithPosition = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereNotNull('position')
            ->whereHas('packageUser', function ($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            })
            ->with(['images', 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            }])
            ->orderBy('position')
            ->take(20)
            ->get();

        // Luego obtenemos usuarios sin posición si aún no llegamos a 20
        if ($usersWithPosition->count() < 20) {
            $remaining = 20 - $usersWithPosition->count();
            $usersWithoutPosition = User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })
                ->whereNotNull('active')
                ->whereNotNull('completed')
                ->whereNull('banned')
                ->whereNull('position')
                ->whereHas('packageUser', function ($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                })
                ->with(['images', 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                }])
                ->inRandomOrder()
                ->take($remaining)
                ->get();

            $users = $usersWithPosition->concat($usersWithoutPosition);
        } else {
            $users = $usersWithPosition;
        }

        $loadedUserIds = $users->pluck('id')->toArray();
        return view('home', compact('users', 'loadedUserIds'));
    }

    public function loadMore($page)
    {
        $perPage = 20;
        $loadedUsers = json_decode(request()->input('loaded_users', '[]'));
        
        // Primero usuarios con posición
        $usersWithPosition = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereNotNull('position')
            ->whereNotIn('id', $loadedUsers)
            ->whereHas('packageUser', function ($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            })
            ->with(['images', 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            }])
            ->orderBy('position')
            ->take($perPage)
            ->get();

        // Completar con usuarios sin posición si es necesario
        if ($usersWithPosition->count() < $perPage) {
            $remaining = $perPage - $usersWithPosition->count();
            $usersWithoutPosition = User::whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                })
                ->whereNotNull('active')
                ->whereNotNull('completed')
                ->whereNull('banned')
                ->whereNull('position')
                ->whereNotIn('id', $loadedUsers)
                ->whereHas('packageUser', function ($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                })
                ->with(['images', 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                }])
                ->inRandomOrder()
                ->take($remaining)
                ->get();

            $users = $usersWithPosition->concat($usersWithoutPosition);
        } else {
            $users = $usersWithPosition;
        }

        $totalRemaining = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereHas('packageUser', function ($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            })
            ->whereNotIn('id', $loadedUsers)
            ->count();

        $hasMore = $totalRemaining > $users->count();

        $html = view('partials.user-grid', ['users' => $users])->render();
        
        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'loadedUsers' => array_merge($loadedUsers, $users->pluck('id')->toArray())
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
