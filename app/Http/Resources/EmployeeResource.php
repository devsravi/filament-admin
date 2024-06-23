<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country_id,
            'state' => $this->state_id,
            'city' => $this->city_id,
            'department' => $this->department_id,
            'zip' => $this->zip_code,
            'birthDay' => $this->birt_date,
            'dateHired' => $this->date_hired
        ];
    }
}
