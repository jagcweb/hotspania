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
    public function index(Request $request) {
        $selected_city = isset($_COOKIE['selected_city']) ? $_COOKIE['selected_city'] : null;

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })
        ->whereNotNull('active')
        ->whereNotNull('completed')
        ->whereNull('banned')
        ->whereNotNull('visible')
        ->whereHas('packageUser', function ($q) {
            $q->where('end_date', '>=', now())
              ->orderBy('end_date', 'desc');
        });

        // Filtrar por ciudad si hay una seleccionada
        if (!empty($selected_city)) {
            $query->whereHas('cities', function($q) use ($selected_city) {
                $q->where('name', $selected_city);
            });
        }

        // Add search condition if search parameter exists
        if ($request->has('search')) {
            $query->where('nickname', 'like', '%' . $request->search . '%');
        }

        $orderByPosition = true;
        $orderByLikes = false;
        switch ($request->get('filter')) {
            case 'disponibles':
                $query->where(function($q) {
                    $q->whereNotNull('available_until')
                      ->where('available_until', '>', Carbon::now('Europe/Madrid'));
                });
                break;
            case 'lgtbi':
                $query->where('gender', 'lgbti');
                break;
            case 'nuevas':
                $orderByPosition = false;
                break;
            case 'ranking':
                $orderByLikes = true;
                $query->whereHas('images', function($q) {
                    $q->select('user_id')
                      ->groupBy('user_id')
                      ->orderBy('likes', 'desc');
                })
                ->with(['images' => function($q) {
                    $q->orderBy('likes', 'desc');
                }])
                ->orderBy(function($query) {
                    $query->select('likes')
                        ->from('images')
                        ->whereColumn('user_id', 'users.id')
                        ->orderBy('likes', 'desc')
                        ->limit(1);
                }, 'desc');
                break;
        }

        $perPage = $orderByPosition ? 20 : 15;

        // Get users with position
        $usersWithPosition = clone $query;
        $usersWithPosition = $usersWithPosition->whereNotNull('position')
            ->with(['images', 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            }]);
            
        if ($orderByPosition) {
            $usersWithPosition = $usersWithPosition->orderBy('position');
        } else {
            if(!$orderByLikes) {
                $usersWithPosition = $usersWithPosition->orderBy('created_at', 'desc');
            }
        }
        
        $usersWithPosition = $usersWithPosition->take($perPage)->get();

        // Get users without position if needed
        if ($usersWithPosition->count() < $perPage) {
            $remaining = $perPage - $usersWithPosition->count();
            $usersWithoutPosition = clone $query;
            $usersWithoutPosition = $usersWithoutPosition->whereNull('position')
                ->with(['images', 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                }]);
            
            if (!$orderByPosition) {
                $usersWithoutPosition = $usersWithoutPosition->orderBy('created_at', 'desc');
            } else {
                $usersWithoutPosition = $usersWithoutPosition->inRandomOrder();
            }
            
            $usersWithoutPosition = $usersWithoutPosition->take($remaining)->get();

            $users = $usersWithPosition->concat($usersWithoutPosition);
        } else {
            $users = $usersWithPosition;
        }

        $loadedUserIds = $users->pluck('id')->toArray();
        return view('home', compact('users', 'loadedUserIds', 'selected_city'));
    }

    public function loadMore($page)
    {
        $perPage = 20;
        $loadedUsers = json_decode(request()->input('loaded_users', '[]'));
        $selected_city = isset($_COOKIE['selected_city']) ? $_COOKIE['selected_city'] : null;
        
        $query = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereNotNull('visible')
            ->whereNotIn('id', $loadedUsers)
            ->whereHas('packageUser', function ($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            });

        // Filtrar por ciudad si hay una seleccionada
        if (!empty($selected_city)) {
            $query->whereHas('cities', function($q) use ($selected_city) {
                $q->where('name', $selected_city);
            });
        }

        // Add search condition if search parameter exists
        if (request()->has('search')) {
            $query->where('nickname', 'like', '%' . request()->search . '%');
        }

        $orderByPosition = true;
        $orderByLikes = false;
        switch (request()->get('filter')) {
            case 'disponibles':
                $query->where(function($q) {
                    $q->whereNotNull('available_until')
                      ->where('available_until', '>', Carbon::now('Europe/Madrid'));
                });
                break;
            case 'lgtbi':
                $query->where('gender', 'lgbti');
                break;
            case 'nuevas':
                $orderByPosition = false;
                break;
            case 'ranking':
                $orderByLikes = true;
                $query->whereHas('images', function($q) {
                    $q->select('user_id')
                      ->groupBy('user_id')
                      ->orderBy('likes', 'desc');
                })
                ->with(['images' => function($q) {
                    $q->orderBy('likes', 'desc');
                }])
                ->orderBy(function($query) {
                    $query->select('likes')
                        ->from('images')
                        ->whereColumn('user_id', 'users.id')
                        ->orderBy('likes', 'desc')
                        ->limit(1);
                }, 'desc');
                break;
        }

        $query->whereNotNull('visible');

        // Primero usuarios con posición
        $usersWithPosition = clone $query;
        $usersWithPosition = $usersWithPosition->whereNotNull('position')
            ->with(['images', 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                  ->orderBy('end_date', 'desc');
            }]);
            
        if ($orderByPosition) {
            $usersWithPosition = $usersWithPosition->orderBy('position');
        } else {
            if(!$orderByLikes) {
                $usersWithPosition = $usersWithPosition->orderBy('created_at', 'desc');
            }
        }
        
        $usersWithPosition = $usersWithPosition->take($perPage)->get();

        // Completar con usuarios sin posición si es necesario
        if ($usersWithPosition->count() < $perPage) {
            $remaining = $perPage - $usersWithPosition->count();
            $usersWithoutPosition = clone $query;
            $usersWithoutPosition = $usersWithoutPosition->whereNull('position')
                ->with(['images', 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())
                      ->orderBy('end_date', 'desc');
                }]);
            
            if (!$orderByPosition) {
                $usersWithoutPosition = $usersWithoutPosition->orderBy('created_at', 'desc');
            } else {
                $usersWithoutPosition = $usersWithoutPosition->inRandomOrder();
            }
            
            $usersWithoutPosition = $usersWithoutPosition->take($remaining)->get();

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
