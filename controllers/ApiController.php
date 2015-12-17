<?php

class ApiController extends Controller
{
    public function __construct()
    {
        $this->beforeFilter('@filterModel');
    }

    public function filterModel($route, $request)
    {
        if (array_key_exists('model', $route->parameters())) {
            $model = $route->parameters()['model'];
            $model = studly_case(str_singular($model));
            if (!class_exists($model)) {
                return Response::json(array('error' => 'Model not found: '.$model), 404);
            }
        }
    }

    public function uploadEntity($model)
    {
        try {
            $entity = $model::upload();
            if ($entity == null)
                return Response::json(["error" => "File upload error"], 404, [], JSON_NUMERIC_CHECK);
            else
                return Response::json($entity, 200, [], JSON_NUMERIC_CHECK);
        } catch (Exception $ex) {
            return Response::json(["error" => "File upload error: ".$ex->getMessage()], 500, [], JSON_NUMERIC_CHECK);
        }
    }

    public function collection($model)
    {
        if (in_array('ApiQueryableInterface', class_implements($model)))
            $query = $model::apiQuery();
        else
            $query = $model::query();

        $with = Request::get('_with');
        if (!is_null($with)) {
            $with = explode(',', $with);
            $query->with($with);
        }
        $this->attachSort($model, $query);

        $ops = array(
            'gt'  => '>',
            'lt'  => '<',
            'gte' => '>=',
            'lte' => '<=',
            'ne'  => '<>',
        );

        foreach (Request::all() as $field => $val) {
            if ($field[0] !== '_') {
                if (!is_array($val)) {
                    if (($pos = strpos($field, '^')) !== false) {
                        $relation      = substr($field, 0, $pos);
                        $field         = substr($field, $pos + 1);
                        $instance      = new $model;
                        $relationTable = $instance->$relation()->getRelated()->getTable();
                        $query->whereHas($relation, function ($query) use ($relationTable, $field, $val) {
                            $query->where($relationTable.'.'.$field, $val);
                        });
                    } else {
                        $query->where($field, $val);
                    }
                } else {
                    if (isset($val['null'])) {
                        if ($val['null'] === 'true') {
                            $query->whereNull($field);
                        } else {
                            $query->whereNotNull($field);
                        }
                    } else {
                        foreach ($ops as $op1 => $op2) {
                            if (isset($val[$op1])) {
                                $query->where($field, $op2, $val[$op1]);
                            }
                        }
                    }
                }
            }
        }

        $this->attachFilters($query);

        $has = Request::get('_has');
        if (!is_null($has))
            $query->has($has);

        $group = Request::get('_group_by');
        if (!is_null($group))
            $query->groupBy($group);


        $count = $query->count();

        $limit = $this->attachLimit($query);
        $offset = $this->attachOffset($query);

        if (Request::get('_select') != null)
            if (!is_null($group)){
                $items = $query->select(DB::raw("SQL_CALC_FOUND_ROWS ".Request::get('_select')))->get();
            }
            else{
                $items = $query->select(DB::raw(Request::get('_select')))->get();
            }
        else{
            $items = $query->get([$model::table().'.*']);
        }

        if (!is_null($group))
            $count = DB::select(DB::raw("SELECT FOUND_ROWS() as count;"))[0]["count"];

//        $queries = DB::getQueryLog();
//        $last_query = end($queries);
//
//        echo "<pre>";
//        print_r($queries);
//        echo "</pre>";
//        die("moare aici");

        return Response::json(array(
            'model'  => $model,
            'items'  => $items->toApiArray(),
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $count
        ), 200, [], JSON_NUMERIC_CHECK);
    }

    public function getQueryCount($query, $group = null)
    {
        if (!is_null($group))
            return DB::select(DB::raw("SELECT FOUND_ROWS() as count;"))[0]["count"];
        else
            return $query->count();
    }

    public function methodNoId($model, $method)
    {
        return $this->method($model, null, $method);
    }

    public function downloadNoId($model, $method)
    {
        return $this->download($model, null, $method);
    }

    public function download($model, $id, $method)
    {
        $apiMethodName = 'api'.ucfirst($method);
        if (!method_exists($model, $apiMethodName))
            return Response::json(array('error' => 'Method '.$apiMethodName.' not found: '.$model), 404);

        if (is_null($id))
            $downloadFile = call_user_func($model.'::'.$apiMethodName);
        else
            $downloadFile = call_user_func_array($model.'::'.$apiMethodName, [$id]);

        return Response::make($downloadFile["content"],
            200,
            ['Content-Type' => $downloadFile['content_type'].';charset=utf-8', 'Content-Disposition' => 'attachment; filename="'.$downloadFile["filename"].'"'],
            JSON_NUMERIC_CHECK);
    }

    public function method($model, $id, $method)
    {

        $apiMethodName = 'api'.ucfirst($method);

        if (!method_exists($model, $apiMethodName)) {
            return Response::json(array('error' => 'Method '.$apiMethodName.' not found: '.$model), 404);
        }

        if (!is_null($id)) {
            return Response::json(array(
                'model'    => $model,
                'response' => call_user_func_array($model.'::'.$apiMethodName, [$id])), 200, [], JSON_NUMERIC_CHECK);
        }

        return Response::json(array(
            'model'    => $model,
            'response' => call_user_func($model.'::'.$apiMethodName)), 200, [], JSON_NUMERIC_CHECK);

    }

    public function deleteEntity($model, $id)
    {
        $model = $model::find($id);
        $model->delete();
        return Response::make($model);
    }

    public function updateEntity($model, $id)
    {
        $model = $model::updateEntity($id, $model, Input::all());
        return Response::make($model);
    }

    public function createEntity($model)
    {
        $model = $model::createEntity($model, Input::all());
        return Response::make($model);
    }

    public function entity($model, $id)
    {

        if (in_array('ApiQueryableInterface', class_implements($model))) {
            $query = $model::apiQuery();
        } else {
            $query = $model::query();
        }

        $with = Request::get('_with');
        if (!is_null($with)) {
            $with = explode(',', $with);
            $query->with($with);
        }

        $item = $query->find($id);

        if (!is_null($item)) {
            return Response::json(array('model' => $model, 'item' => $item->toApiArray()), 200, [], JSON_NUMERIC_CHECK);
        } else {
            return Response::json(array('error' => 'Item not found'), 404);
        }
    }

    public function relation($model, $id, $relation)
    {
        $model = studly_case(str_singular($model));

        $sort = Request::get('_sort');

        $query = $model::with(array($relation => function ($query) use ($sort) {
                if (!is_null($sort)) {
                    $sort = explode(',', $sort);
                    foreach ($sort as $fieldDir) {
                        if (strpos($fieldDir, ':') !== false) {
                            list($field, $dir) = explode(':', $fieldDir);
                        } else {
                            $field = $fieldDir;
                            $dir   = 'asc';
                        }
                        $query->orderBy($field, $dir);
                    }
                }
            }));

        if (!class_exists($model)) {
            return Response::json(array('error' => 'Model not found: '.$model), 404);
        }

        $item = $query->find($id);

        if (!is_null($item)) {
            $relationModel = $item->$relation()->getRelated();
            $response      = array('model' => get_class($relationModel));
            if ($item->$relation instanceof Collection) {
                $response['items'] = $item->$relation->toApiArray();
            } else {
                $response['item'] = $item->$relation->toApiArray();
            }
            return Response::json($response, 200, [], JSON_NUMERIC_CHECK);
        } else {
            return Response::json(array('error' => 'Item not found'), 404);
        }
    }

    public function royalty_stream_file_pdf($id)
    {
        $royaltyStreamFile = RoyaltyStreamFile::find($id);
        $user              = Auth::getUser();
        if (is_null($royaltyStreamFile) ||
            (!$user->isAdmin() && $royaltyStreamFile->company_id !== $user->company_id)
        ) {
            return Response::make('Not Found', 404);
        }
        $pdfFilename = $royaltyStreamFile->stream_file_name;
        $pdfFilename = substr($pdfFilename, strrpos($pdfFilename, '/') + 1);
        $pdfFilename = str_replace('csv', 'pdf', $pdfFilename);
        $pdfFile     = storage_path("pdf/$pdfFilename");
        return Response::make(file_get_contents($pdfFile), 200, ['Content-Type' => 'application/pdf']);
    }

    public function attachFilters($query)
    {
        $filters = Request::get('_filter');
        if (count($filters) > 0) {
            $orFilters = [];
            foreach ($filters as $filter) {
                list($field, $value) = explode(':', $filter);

                if (strpos($value, '|or') !== false)
                    $orFilters[$field] = str_replace("|or", "", $value);
                else
                    $this->attachWhere($query, $value, $field);
            }
            if (count($orFilters) > 0)
                $query->where(function ($query) use ($orFilters) {
                    foreach ($orFilters as $field => $value)
                        $query->orWhere($field, "like", "%".$value."%");
                });
        }
    }

    public function attachWhere($query, $value, $field)
    {
        if ($value == "null")
            $query->whereNull($field);
        else if ($value == "!null")
            $query->whereNotNull($field);
        elseif (strpos($value, '=') !== false)
            $query->where($field, "=", str_replace("=", "", $value));
        elseif (strpos($value, '!') !== false)
            $query->where($field, "not like", "%".str_replace("!", "", $value)."%");
        else
            $query->where($field, "like", "%".$value."%");
    }

    public function attachOffset($query)
    {
        $offset = Request::get('_offset');
        if (is_null($offset))
            $offset = 0;
        $offset = (int)$offset;
        $query->skip($offset);
        return $offset;
    }

    public function attachLimit($query)
    {
        $limit = Request::get('_limit');
        if (is_null($limit))
            $limit = 10;
        $limit = (int)$limit;
        $query->take($limit);
        return $limit;
    }

    public function attachSort($model, $query)
    {
        $sort = Request::get('_sort');
        if (!is_null($sort)) {
            $sort = explode(',', $sort);
            foreach ($sort as $fieldDir) {
                if (strpos($fieldDir, ':') !== false) {
                    list($field, $dir) = explode(':', $fieldDir);
                } else {
                    $field = $fieldDir;
                    $dir   = 'asc';
                }
                if (strpos($fieldDir, '.') !== false) {
                    list($entityName, $entityField) = explode('.', $field);
                    $entityName      = ucfirst($entityName);
                    $entityNameTable = $entityName::table();
                    $query->join($entityNameTable, $model::table().'.'.$entityNameTable.'_id', '=', $entityNameTable.'.id');
                    $query->orderBy($entityNameTable.'.'.$entityField, $dir);
                } else {
                    $query->orderBy($model::table().".".$field, $dir);
                }
            }
        }
    }
}
