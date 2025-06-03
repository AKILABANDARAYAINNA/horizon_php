<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MongoPropertyService;

class PropertyFilter extends Component
{
    public $property_type = '';
    public $for_sale_or_rent = '';
    public $location = '';
    public $max_price = '';

    protected $propertyService;

    public function mount()
    {
        $this->propertyService = app(MongoPropertyService::class);
    }

    public function render()
    {
        $filters = [
            'property_type' => $this->property_type,
            'for_sale_or_rent' => $this->for_sale_or_rent,
            'location' => $this->location,
        ];

        if (!empty($this->max_price)) {
            $filters['price'] = ['$lte' => (float)$this->max_price];
        }

        $properties = $this->propertyService->filterProperties($filters);

        return view('livewire.property-filter', [
            'properties' => $properties,
        ]);
    }
}
