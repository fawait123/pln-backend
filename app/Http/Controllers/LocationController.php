<?php

namespace App\Http\Controllers;

use App\Enums\AuthPermissionEnum;
use App\Enums\PermissionEnum;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\ResponseMiddleware;
use App\Models\Location;
use App\Traits\HasApiResource;
use App\Traits\HasList;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: [ResponseMiddleware::class])]
#[Group(name: 'Master Location')]
class LocationController extends Controller implements HasMiddleware
{
    #[DoNotDiscover]
    public static function middleware()
    {
        return [
            new Middleware(AuthPermissionEnum::AUTH_API->value),
            new Middleware(
                PermissionMiddleware::using(
                    [
                        PermissionEnum::LOCATION
                    ]
                ),
                except: ['list']
            )
        ];
    }

    use HasList, HasApiResource;

    protected $model = Location::class;
    protected array $search = ['name', 'slug'];
    protected array $with = [];
    protected $rules = [
        'name' => 'required',
        'slug' => 'required',
        'description' => 'nullable',
        'lat' => 'required',
        'lon' => 'required',
        'color' => 'required'
    ];
}
