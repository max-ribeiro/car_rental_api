<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AbstractRepository {
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    /**
     * Return entity the records
     *
     * @return Collection
     */
    public function get(): Collection {
        return $this->model->get();
    }

    /**
     * Set the filters to be applied on the entity
     *
     * @param string $filters
     * @return void
     */
    public function setFilters(string $filters) {
        $filters = explode(';', $filters);
        foreach($filters as $filter) {
            $filter = explode(':', $filter);
            $this->model = $this->model->where($filter[0], $filter[1], $filter[2]);
        }
    }

    /**
     * Set the params sent related to the entity
     *
     * @param string $params
     * @return void
     */
    public function setParams(string $params) {
        $this->model = $this->model->selectRaw($params);
    }

    /**
     * Set the params sent related to associated entity
     *
     * @param string $params
     * @return void
     */
    public function setRelatedRecordsParams(string $params) {
        $this->model = $this->model->with($params);
    }
}
