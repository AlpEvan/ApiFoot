<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Models;

use EvanAlpst\ApiFoot\Core\Database;

abstract class AbstractModel
{
    /**
     * Defines primary key
     *
     * @var string|null
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
                    if ($this->casts[$property] === 'int') {
                        $this->$property = (int)$value;
                    } elseif ($this->casts[$property] === 'float') {
                        $this->$property = (float)$value;
                    } elseif ($this->casts[$property] === 'bool') {
                        $this->$property = (bool)$value;
                    } elseif ($this->casts[$property] === 'datetime') {
                        $this->$property = $value ? new \DateTime($value) : null;
                    } else {
                        throw new \InvalidArgumentException("Unsupported cast type: {$this->casts[$property]}");
                    }
                } else {
                    $this->$property = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Find a model by its primary key
     *
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        $primaryKey = static::$primaryKey;

        if (!$primaryKey) {
            throw new \LogicException("Primary key name must be set");
        }

        $tableName = static::getTableName();

        $statement = Database::connection()
            ->prepare("SELECT * FROM {$tableName} WHERE {$primaryKey} = :id");

        $statement->execute([':id' => $id]);

        $row = $statement->fetch();

        return $row ? (new static())->fill($row) : null;
    }

    /**
     * Get the table name from the class name
     *
     * @return string
     */
    protected static function getTableName(): string
    {
        return (new \ReflectionClass(static::class))->getShortName();
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
            throw new \LogicException("Primary key property must be null when creating");
        }

        $model = (new static())->fill($attributes);

        $model->save();

        return $model;
    }

    /**
     * Save Model
     *
     * @return bool
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
     * Delete the current model
     *
     * @return bool
     */
    public function delete(): bool
    {
        $primaryKey = static::$primaryKey;

        if (!$primaryKey) {
            throw new \LogicException("Primary key name must be set");
        }

        if ($this->$primaryKey === null) {
            throw new \LogicException("Cannot delete a model that hasn't been saved");
        }

        $tableName = static::getTableName();

        $statement = Database::connection()
            ->prepare("DELETE FROM {$tableName} WHERE {$primaryKey} = :id");

        return $statement->execute([':id' => $this->$primaryKey]);
    }

    /**
     * Insert a new row
     *
     * @return bool
     */
    abstract public function insert(): bool;

    /**
     * Update an existing row
     *
     * @return bool
     */
    abstract public function update(): bool;
}