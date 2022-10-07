<?php

namespace App\Repositories;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method static $this withquery($query)
 * @method static $this query($query)
 * @method static Collection get()
 * @method static Model first()
 * @method static Model find($id)
 * @method static  Paginator paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * whereDate / whereMonth / whereDay / whereYear / whereTime
 */
abstract class Repository
{
    protected $model_name;
    protected $model;
    protected $withCache = false;
    protected $query;
    private $withInit = true;


    abstract protected function init(Builder $model);

    public function __construct($model = null)
    {
        $this->model =  $this->modelInit($model);
    }


    public function withCache($mehod, $data, $prefix = null)
    {
        //TODO CACHE 
        return $data;
    }

    public function getModel($model = null)
    {
        return  $model ?: $this->resolveModelName(get_called_class());
    }

    private function modelInit($model = null)
    {
        $model_name  = $this->getModel($model);

        return $model_name::query();
    }

    private static function resolveModelName(string $model_name)
    {
        $need_class = 'App\\Models\\' . Str::replace("Repository", '', Str::after($model_name, 'Repositories\\'));

        return class_exists($need_class) ? $need_class : abort(500, "Model not found");
    }

    protected function resolveAfterMethod($data, $method)
    {
        $method = 'after' . ucfirst($method);

        if (method_exists(static::class, "$method")) {
            return  $this->$method($data);
        } elseif (method_exists(static::class, "after")) {
            return  $this->after($data);
        }

        return $data;
    }


    public static function withoutInit()
    {
        return  static::query()->offInit();
    }

    private function  offInit()
    {
        $this->withInit = false;
        return $this;
    }

    private function makeInit()
    {
        return $this->withInit ? $this->init($this->model): $this->model;
    }

    private function execute($method = 'get', $args = [])
    {
        $queried  = $this->query instanceof Closure ?  $this->query($this->model) : $this->makeInit();

        $data = call_user_func_array([$queried, $method], $args);

        return $this->withCache ?
            $this->withCache(
                $method,
                $this->resolveAfterMethod($data, $method)
            ) :
            $this->resolveAfterMethod($data, $method);
    }

    public static function query($call = null)
    {
        $service = new static;

        if ($call instanceof Closure) {
            $service->query = $call;
        }

        return $service;
    }

    public static function withQuery($call)
    {
        $service = new static;

        if ($call instanceof Closure) {
            $service->query = function ($model) use ($call, $service) {
                return $call($service->init($model));
            };
        }

        return $service;
    }


    public function __call($name, $args)
    {
        return $this->execute($name, $args);
    }

    public static function __callStatic($name, $args)
    {
        return static::query()->execute($name, $args);
    }
}
