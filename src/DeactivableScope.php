<?php

namespace EvoStudio\Deactivation;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DeactivableScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedDeactivatedAtColumn());
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder): void
    {
        $this->addWithDeactivated($builder);
        $this->addWithoutDeactivated($builder);
        $this->addOnlyDeactivated($builder);
    }

    /**
     * Add the with-deactivated extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithDeactivated(Builder $builder): void
    {
        $builder->macro('withDeactivated', function (Builder $builder, $withDeactivated = true) {
            if (! $withDeactivated) {
                return $builder->withoutDeactivated();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-deactivated extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutDeactivated(Builder $builder): void
    {
        $builder->macro('withoutDeactivated', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $model->getQualifiedDeactivatedAtColumn()
            );

            return $builder;
        });
    }

    /**
     * Add the only-deactivated extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyDeactivated(Builder $builder): void
    {
        $builder->macro('onlyDeactivated', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedDeactivatedAtColumn()
            );

            return $builder;
        });
    }
}
