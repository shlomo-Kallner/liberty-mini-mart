<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Database\Eloquent\Collection;
use App\Product;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Product) {
            return $this->resource->toContentArray();
        } elseif ($this->resource instanceof Collection) {
            return Product::getContentArrays($this->resource);
        } else {
            return parent::toArray($request);
        }
    }
}
