<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Database\Eloquent\Collection;
use App\Product;

class ProductsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->collection instanceof Collection) {
            return [
                'data' => Product::getContentArrays($this->collection)
            ];
        } else {
            return parent::toArray($request);
        }
    }
}
