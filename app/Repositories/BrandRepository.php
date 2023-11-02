<?php
namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository {
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Return entity the records
     *
     * @return Collection
     */
    public function get(): Collection {
        return $this->brand->get();
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
            $this->brand = $this->brand->where($filter[0], $filter[1], $filter[2]);
        }
    }

    /**
     * Set the params sent related to the entity
     *
     * @param string $params
     * @return void
     */
    public function setParams(string $params) {
        $this->brand = $this->brand->selectRaw($params);
    }

    /**
     * Set the params sent related to associated entity
     *
     * @param string $params
     * @return void
     */
    public function setRelatedRecordsParams(string $params) {
        $this->brand = $this->brand->with($params);
    }
}
