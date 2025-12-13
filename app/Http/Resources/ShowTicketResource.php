<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowTicketResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'customer'=> CustomerResource::make($this->whenLoaded('customer')),
            'attachments' => AttachmentResource::collection($this->getMedia('attachments')),
            'answered_at' => $this->answered_at,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
