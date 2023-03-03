<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomor' => $this->nomor,
            'date' => $this->date,
            'title' => $this->title,
            'category' => $this->category->name,
            'priority' =>  $this->priority,
            'status' => $this->status,
            'detail_trouble' => $this->detail_trouble,
            'requester' => $this->userRequester->name,
            'technician' => $this->userTechnician->name,
            'created' => $this->created_at->format("d F Y"),
        ];
    }
}
