<?php

namespace EvoStudio\Deactivation;

use Carbon\Carbon;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withDeactivated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyDeactivated()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withoutDeactivated()
 */
trait Deactivable
{

    /**
     * Boot the deactivable trait for a model.
     *
     * @return void
     */
    public static function bootDeactivable(): void
    {
        static::addGlobalScope(new DeactivableScope);
    }

    /**
     * Initialize the deactivable trait for an instance.
     *
     * @return void
     */
    public function initializeDeactivable(): void
    {
        if (! isset($this->casts[$this->getDeactivatedAtColumn()])) {
            $this->casts[$this->getDeactivatedAtColumn()] = 'datetime';
        }
    }

    /**
     * Get the name of the "deactivated at" column.
     *
     * @return string
     */
    public function getDeactivatedAtColumn()
    {
        return defined('static::DEACTIVATED_AT')
            ? static::DEACTIVATED_AT
            : 'deactivated_at';
    }

    /**
     * Activate a deactivable model instance.
     *
     * @return bool
     */
    public function activate(): bool
    {
        $this->{$this->getDeactivatedAtColumn()} = null;
        return $this->save();
    }

    /**
     * Deactivate a deactivable model instance.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        $this->{$this->getDeactivatedAtColumn()} = Carbon::now();
        return $this->save();
    }

    /**
     * Get the fully qualified "deactivated at" column.
     *
     * @return string
     */
    public function getQualifiedDeactivatedAtColumn()
    {
        return $this->qualifyColumn($this->getDeactivatedAtColumn());
    }

    /**
     * Returns value of "deactivated at" column in boolean form
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->{$this->getDeactivatedAtColumn()});
    }

    /**
     * Allow to set value for "deactivated at" column like a bool field
     *
     * @return bool
     */
    public function setIsActiveAttribute($value)
    {
        $this->attributes[$this->getDeactivatedAtColumn()] = ! empty($value) && (bool) $value
            ? null
            : Carbon::now();
    }
}
