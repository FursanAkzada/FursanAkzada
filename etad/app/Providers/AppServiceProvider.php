<?php

namespace App\Providers;

use App\View\Components\Form\Buttons\Back;
use App\View\Components\Form\Buttons\Draft;
use App\View\Components\Form\Buttons\Save;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\Master\Entities\Perusahaan;
use Modules\Master\Entities\PerusahaanAlamatFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        set_time_limit(350);
        ini_set('max_execution_time', 180);
        ini_set('max_input_time', 180);
        ini_set('max_input_vars', 3000);
        /* Component */
        Blade::component('btn-save', Save::class);
        Blade::component('btn-draft', Draft::class);
        Blade::component('btn-back', Back::class);
        /* Macro */
        Builder::macro(
            'whereLike',
            function ($attributes, string $searchTerm) {
                $this->where(
                    function (Builder $query) use ($attributes, $searchTerm) {
                        foreach (\Arr::wrap($attributes) as $attribute) {
                            $query->when(
                                str_contains($attribute, '.'),
                                function (Builder $query) use ($attribute, $searchTerm) {
                                    $buffer = explode('.', $attribute);
                                    $attributeField = array_pop($buffer);
                                    $relationPath = implode('.', $buffer);
                                    $query->orWhereHas(
                                        $relationPath,
                                        function (Builder $query) use ($attributeField, $searchTerm) {
                                            $query->where($attributeField, 'LIKE', "%{$searchTerm}%");
                                        }
                                    );
                                },
                                function (Builder $query) use ($attribute, $searchTerm) {
                                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                                }
                            );
                        }
                    }
                );
                return $this;
            }
        );
        if (env('FORCE_HTTPS', false)) { // Default value should be false for local server
            URL::forceScheme('https');
        }
    }
}
