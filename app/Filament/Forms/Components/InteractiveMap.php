<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class InteractiveMap extends Field
{
    protected string $view = 'filament.forms.components.interactive-map';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (InteractiveMap $component, $state): void {
            // Get the current form state to access latitude, longitude, and radius
            $formState = $component->getContainer()->getStatePath() ? 
                $component->getContainer()->getState() : 
                $component->getLivewire()->data;
                
            $component->state([
                'latitude' => $formState['latitude'] ?? 40.7128,
                'longitude' => $formState['longitude'] ?? -74.0060, 
                'radius_miles' => $formState['radius_miles'] ?? 5,
            ]);
        });

        $this->afterStateUpdated(static function (InteractiveMap $component, $state): void {
            // Update the parent form fields when map is interacted with
            if (isset($state['latitude'])) {
                $component->getLivewire()->data['latitude'] = $state['latitude'];
            }
            if (isset($state['longitude'])) {
                $component->getLivewire()->data['longitude'] = $state['longitude'];
            }
            if (isset($state['radius_miles'])) {
                $component->getLivewire()->data['radius_miles'] = $state['radius_miles'];
            }
        });
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }
}
