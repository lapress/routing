<?php

namespace LaPress\Routing\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
