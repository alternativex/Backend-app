<?php

use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection implements ApiArrayableInterface {
    public function toApiArray() {
        return array_map(function($value) {
            return $value instanceof ApiArrayableInterface ? $value->toApiArray() : $value;
        }, $this->items);
    }
}
