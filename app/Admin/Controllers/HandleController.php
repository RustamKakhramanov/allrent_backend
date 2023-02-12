<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\HandleController as ParentController; 
use Encore\Admin\Actions\Action;
use Encore\Admin\Actions\GridAction;
use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\RowAction;
use Encore\Admin\Widgets\Form;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class HandleController extends ParentController
{

   
    public function handleAction(Request $request)
    {
        $action = $this->resolveActionInstance($request);

        $model = null;
        $arguments = [];

        if ($action instanceof GridAction) {
            $model = $action->retrieveModel($request);
            $arguments[] = $model;
        }

        if (!$action->passesAuthorization($model)) {
            return $action->failedAuthorization();
        }

        if ($action instanceof RowAction) {
            $action->setRow($model);
        }
dd($action->validate($request));

        try {
            $response = $action->validate($request)->handle(
                ...$this->resolveActionArgs($request, ...$arguments)
            );
        } catch (Exception $exception) {
            return Response::withException($exception)->send();
        }

        if ($response instanceof Response) {
            return $response->send();
        }
    }
}
