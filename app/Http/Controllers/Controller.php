<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\Middleware;
use ReflectionClass;
use Spatie\Permission\Middleware\PermissionMiddleware;

abstract class Controller
{
    use AuthorizesRequests;
    
    protected $title = '';
    protected $breadcrumbs = [];
    protected $view = '';
    protected $permission_name = null;
    protected $middleware = [];

    public function setupConstruct()
    {
        view()->share('permission_name', $this->permission_name);
        view()->share('resource_view', $this->view);
        view()->share('title', $this->title);
    }

    protected function setBreadcrumbs(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        view()->share('breadcrumbs', $this->breadcrumbs);
    }

    public static function middleware(): array
    {
        $instance = new static;
        $map = $instance->middleware ?? [];
        $result = [];

        $allMethods = collect((new ReflectionClass($instance))->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->pluck('name')
            ->reject(fn($m) => in_array($m, ['__construct', 'middleware']))
            ->values()
            ->all();

        foreach ($map as $methods => $permission) {
            $only = [];
            $except = [];

            if (is_array($methods)) {
                $only = $methods;
            } elseif (is_string($methods)) {
                if ($methods === '*') {
                    $only = $allMethods;
                } elseif (str_starts_with($methods, 'except:')) {
                    $except = explode(',', str_replace('except:', '', $methods));
                    $only = array_diff($allMethods, $except);
                } else {
                    $only = explode(',', $methods);
                }
            }

            $result[] = new Middleware(
                PermissionMiddleware::using($permission),
                only: $only ?: null,
                except: $except ?: null
            );
        }

        return $result;
    }
}
