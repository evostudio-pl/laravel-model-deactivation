<?php

namespace EvoStudio\Deactivation;

use Illuminate\Database\Schema\Blueprint;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->registerDeactivation();
    }

    private function registerDeactivation(): void
    {
        Blueprint::macro('deactivation', function ($column = 'deactivated_at') {
            $this->timestamp($column)->nullable();
        });

        Blueprint::macro('dropDeactivation', function ($column = 'deactivated_at') {
            $this->dropColumn([$column]);
        });
    }
}
