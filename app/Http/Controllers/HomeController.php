<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\StorageHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $selected_zone = isset($_COOKIE['selected_zone']) ? $_COOKIE['selected_zone'] : null;

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })
        ->whereNotNull('active')
        ->whereNotNull('completed')
        ->whereNull('banned')
        ->whereNotNull('users.visible')
        ->whereHas('packageUser', function ($q) {
            $q->where('end_date', '>=', now())
              ->orderBy('end_date', 'desc');
        });

        // Filtrar por ciudad si hay una seleccionada
        if (!empty($selected_city)) {
            $query->whereHas('cities', function($q) use ($selected_city) {
                $q->where('cities.id', $selected_city);
            });
        }

        // Filtrar por zona si hay una seleccionada
        /*if (!empty($selected_zone)) {
            $query->whereHas('zones', function($q) use ($selected_zone) {
                $q->where('zones.id', $selected_zone);
            });
        }*/

        // Add search condition if search parameter exists
        if ($request->has('search')) {
            $query->where('nickname', 'like', '%' . $request->search . '%');
        }

        $orderByPosition = true;
        $orderByLikes = false;
        $orderByBestPhoto = false;
        $orderByNew = false;
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
                $orderByNew = true;
                break;
            case 'fotos':
                $orderByBestPhoto = true;
                $orderByPosition = false;
                break;
            case 'ranking':
                $orderByLikes = true;
                $orderByPosition = false;
                break;
        }

        $perPage = $orderByPosition ? 20 : 15;

        // Special handling for ranking by likes
        if ($orderByLikes) {
            $users = $query->leftJoin('images', 'images.user_id', '=', 'users.id')
                ->leftJoin('image_likes', 'image_likes.image_id', '=', 'images.id')
                ->select('users.*', \DB::raw('(COALESCE(SUM(images.visits), 0) * 0.2) + (COUNT(image_likes.id) * 0.5) + (COALESCE(users.visits, 0) * 1) as total_points'))
                ->groupBy('users.id')
                ->having('total_points', '>', 0)
                ->orderByDesc('total_points')
                ->with(['images' => function ($q) {
                    $q->withCount('likes')->orderByDesc('likes_count');
                }, 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())
                    ->orderBy('end_date', 'desc');
                }])
                ->take($perPage)
                ->get();
        } elseif ($orderByBestPhoto) {
            $users = $query->leftJoin(\DB::raw('(
                SELECT 
                    user_id,
                    MAX((COALESCE(visits, 0) * 0.2) + (
                        SELECT COUNT(*) FROM image_likes WHERE image_likes.image_id = images.id
                    ) * 0.5) as best_photo_points
                FROM images 
                GROUP BY user_id
            ) as best_images'), 'best_images.user_id', '=', 'users.id')
                ->select('users.*', 'best_images.best_photo_points')
                ->whereNotNull('best_images.best_photo_points')
                ->where('best_images.best_photo_points', '>', 0)
                ->orderByDesc('best_images.best_photo_points')
                ->with(['images' => function ($q) {
                    $q->withCount('likes')->orderByDesc('likes_count');
                }, 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())->orderBy('end_date', 'desc');
                }])
                ->take($perPage)
                ->get();
        } elseif ($orderByNew) {
            $users = $query->orderBy('id', 'desc')
            ->with(['images' => function ($q) {
                $q->withCount('likes')->orderByDesc('likes_count');
            }, 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                ->orderBy('end_date', 'desc');
            }])
            ->take($perPage)
            ->get();
        } else {
            // Original logic for other filters
            $usersWithPosition = clone $query;
            $usersWithPosition = $usersWithPosition->whereNotNull('position')
            ->with(['images', 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                ->orderBy('end_date', 'desc');
            }]);

            if ($orderByPosition) {
            $usersWithPosition = $usersWithPosition->orderBy('position');
            } else {
            $usersWithPosition = $usersWithPosition->orderBy('created_at', 'desc');
            }

            $usersWithPosition = $usersWithPosition->take($perPage)->get();

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
        }

        $loadedUserIds = $users->pluck('id')->toArray();
        return view('home', compact('users', 'loadedUserIds', 'selected_city'));
    }

    public function loadMore($page) {
        $perPage = 20;
        $loadedUsers = json_decode(request()->input('loaded_users', '[]'));
        $selected_city = isset($_COOKIE['selected_city']) ? $_COOKIE['selected_city'] : null;
        
        $query = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->whereNotIn('id', $loadedUsers)
            ->whereNotNull('users.visible')
            ->whereHas('packageUser', function ($q) {
                $q->where('end_date', '>=', now())
                ->orderBy('end_date', 'desc');
            });

        // Filtrar por ciudad si hay una seleccionada
        if (!empty($selected_city)) {
            $query->whereHas('cities', function($q) use ($selected_city) {
                $q->where('cities.id', $selected_city);
            });
        }

        // Filtrar por zona si hay una seleccionada
        /*if (!empty($selected_zone)) {
            $query->whereHas('zones', function($q) use ($selected_zone) {
                $q->where('zones.id', $selected_zone);
            });
        }*/

        // Add search condition if search parameter exists
        if (request()->has('search')) {
            $query->where('nickname', 'like', '%' . request()->search . '%');
        }

        $orderByPosition = true;
        $orderByLikes = false;
        $orderByBestPhoto = false;
        $orderByNew = false;
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
                $orderByNew = true;
                break;
            case 'fotos':
                $orderByBestPhoto = true;
                $orderByPosition = false;
                break;
            case 'ranking':
                $orderByLikes = true;
                $orderByPosition = false;
                break;
        }

        // Special handling for ranking by likes
        if ($orderByLikes) {
            $users = $query->leftJoin('images', 'images.user_id', '=', 'users.id')
                ->leftJoin('image_likes', 'image_likes.image_id', '=', 'images.id')
                ->select('users.*', \DB::raw('(COALESCE(SUM(images.visits), 0) * 0.2) + (COUNT(image_likes.id) * 0.5) + (COALESCE(users.visits, 0) * 1) as total_points'))
                ->having('total_points', '>', 0)
                ->orderByDesc('total_points')
                ->with(['images' => function ($q) {
                    $q->withCount('likes')->orderByDesc('likes_count');
                }, 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())->orderBy('end_date', 'desc');
                }])
                ->take($perPage)
                ->get();
        } elseif ($orderByBestPhoto) {
            $users = $query->leftJoin(\DB::raw('(
                SELECT 
                    user_id,
                    MAX((COALESCE(visits, 0) * 0.2) + (
                        SELECT COUNT(*) FROM image_likes WHERE image_likes.image_id = images.id
                    ) * 0.5) as best_photo_points
                FROM images 
                GROUP BY user_id
            ) as best_images'), 'best_images.user_id', '=', 'users.id')
                ->select('users.*', 'best_images.best_photo_points')
                ->whereNotNull('best_images.best_photo_points')
                ->where('best_images.best_photo_points', '>', 0)
                ->orderByDesc('best_images.best_photo_points')
                ->with(['images' => function ($q) {
                    $q->withCount('likes')->orderByDesc('likes_count');
                }, 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())->orderBy('end_date', 'desc');
                }])
                ->take($perPage)
                ->get();
        } elseif ($orderByNew) {
            $users = $query->orderBy('id', 'desc')
            ->with(['images' => function ($q) {
                $q->withCount('likes')->orderByDesc('likes_count');
            }, 'packageUser' => function($q) {
                $q->where('end_date', '>=', now())
                ->orderBy('end_date', 'desc');
            }])
            ->take($perPage)
            ->get();
        } else {
            // Original logic for other filters
            // Usuarios con posición
            $usersWithPosition = clone $query;
            $usersWithPosition = $usersWithPosition->whereNotNull('position')
                ->with(['images', 'packageUser' => function($q) {
                    $q->where('end_date', '>=', now())->orderBy('end_date', 'desc');
                }]);

            if ($orderByPosition) {
                $usersWithPosition = $usersWithPosition->orderBy('position');
            } else {
                $usersWithPosition = $usersWithPosition->orderBy('created_at', 'desc');
            }

            $usersWithPosition = $usersWithPosition->take($perPage)->get();

            // Usuarios sin posición
            if ($usersWithPosition->count() < $perPage) {
                $remaining = $perPage - $usersWithPosition->count();
                $usersWithoutPosition = clone $query;
                $usersWithoutPosition = $usersWithoutPosition->whereNull('position')
                    ->with(['images', 'packageUser' => function($q) {
                        $q->where('end_date', '>=', now())->orderBy('end_date', 'desc');
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

    public function privacyPolicies() {
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

    public function getDocument($filename) {
        $filePath = public_path('documents/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'Documento no encontrado');
        }

        $file = file_get_contents($filePath);

        return new Response($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function getZonesFromCity($cityId) {
        $zones = \App\Models\Zone::where('city_id', $cityId)->orderBy('name', 'asc')->get();

        if ($zones->isEmpty()) {
            return response()->json(['error' => 'No se encontraron zonas para esta ciudad.'], 404);
        }

        return response()->json($zones);
    }
}
