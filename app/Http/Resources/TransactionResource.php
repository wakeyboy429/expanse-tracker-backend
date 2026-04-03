<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Transaction",
    title: "Transaction",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "Grocery Store"),
        new OA\Property(property: "amount", type: "number", format: "float", example: -50.25),
        new OA\Property(property: "type", type: "string", example: "expense"),
        new OA\Property(property: "created_at", type: "string", example: "2026-04-03 10:30:00")
    ]
)]
class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'amount'     => (float) $this->amount,
            'type'       => $this->amount < 0 ? 'expense' : 'income',
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
