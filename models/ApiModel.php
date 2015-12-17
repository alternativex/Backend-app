<?php

class ApiModel extends Eloquent implements ApiArrayableInterface {

    protected function accessors()
    {
        return [];
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public function toApiArray() {
        $result = $this->toArray();
        foreach ($this->accessors() as $accessorKey => $accessorValue) {
            $result[$accessorKey] = $accessorValue;
        }
        return $result;
    }

    public static function updateEntities($ids, $model, $properties){
        $properties = array_diff_key($properties, $model->accessors());
        return $model::whereIn('id', $ids)->update($properties);
    }

    public static function updateEntity($id, $model, $properties){
        $model = $model::find($id);
        $properties = array_diff_key($properties, $model->accessors());
        $model->fill($properties);
        $model->save();
        return $model;
    }

    public static function createEntity($model, $properties){
        $model = new $model();
        $model->fill($properties);
        $model->save();
        return $model;
    }

    public function newCollection(array $models = array()) {
        return new Collection($models);
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = array();

        foreach ($this->getArrayableRelations() as $key => $value)
        {
            if (in_array($key, $this->hidden)) continue;

            // If the values implements the Arrayable interface we can just call this
            // toArray method on the instances which will convert both models and
            // collections to their proper array form and we'll set the values.
            if ($value instanceof ApiArrayableInterface)
            {
                $relation = $value->toApiArray();                
            }

            // If the value is null, we'll still go ahead and set it in this list of
            // attributes since null is used to represent empty relationships if
            // if it a has one or belongs to type relationships on the models.
            elseif (is_null($value))
            {
                $relation = $value;
            }

            // If the relationships snake-casing is enabled, we will snake case this
            // key so that the relation attribute is snake cased in this returned
            // array to the developers, making this consistent with attributes.
            if (static::$snakeAttributes)
            {
                $key = snake_case($key);
            }

            // If the relation value has been set, we will set it on this attributes
            // list for returning. If it was not arrayable or null, we'll not set
            // the value on the array because it is some type of invalid value.
            if (isset($relation) || is_null($value))
            {
                $attributes[$key] = $relation;
            }
        }

        return $attributes;
    }
}
