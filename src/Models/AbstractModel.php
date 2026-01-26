<?php

declare(strict_types=1);

namespace TheWallet\Models;

abstract class AbstractModel
{
    /**
     * Defines primary key
     *
     * @var string
     */
    protected static ?string $primaryKey = null;

    /**
     * Allow to casts properties
     *
     * @var array
     */
    protected array $casts = [];

    /**
     * Assign values to properties
     *
     * @param array $attributes
     * @return self
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                if (array_key_exists($property, $this->casts)) {
                    if ($this->casts[$property] === 'float') {
                        $this->$property = (float)$value;
                    } elseif ($this->casts[$property] === 'datetime') {
                        $this->$property = new \DateTime($value);
                    } else {
                        throw new \InvalidArgumentException("Unsupported cast type");
                    }
                } else {
                    $this->$property = $value;
                }
            }
        }

        return $this;
    }


    /**
     * Create a new row
     *
     * @param array $attributes
     * @return self
     */
    public static function create(array $attributes): self
    {
        $primaryKey = static::$primaryKey;

        if (!$primaryKey) {
            throw new \LogicException("Primary key name must be set");
        }

        if (array_key_exists($primaryKey, $attributes)) {
            throw new \LogicException("Primary key property must be null");
        }

        $model = (new static())->fill($attributes);

        $model->save();

        return $model;
    }

    /**
     * Save Model
     */
    public function save(): bool
    {
        $primaryKey = static::$primaryKey;

        if (!$primaryKey) {
            throw new \LogicException("Primary key name must be set");
        }

        if ($this->$primaryKey === null) {
            return $this->insert();
        }

        return $this->update();
    }

    /**
     * insert a new row
     *
     * @return bool
     */
    abstract public function insert(): bool;

    /**
     * update an existing row
     *
     * @return bool
     */
    abstract public function update(): bool;
}
