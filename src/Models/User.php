<?php

declare(strict_types=1);

namespace EvanAlpst\ApiFoot\Models;

use EvanAlpst\ApiFoot\Core\Database;

class User extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected static ?string $primaryKey = 'userId';

    /**
     * Primary key
     *
     * @var integer|null
     */
    public ?int $userId = null;

    /**
     * Email field
     *
     * @var string|null
     */
    public ?string $email = null {
        set => $this->email = trim($value);
    }

    /**
     * Password (hash) field
     *
     * @var string|null
     */
    public ?string $password = null {
        set => $this->password = trim($value);
    }

    /**
     * Firstname field
     *
     * @var string|null
     */
    public ?string $firstname = null {
        set => $this->firstname = trim($value);
    }

    /**
     * lastname field
     *
     * @var string|null
     */
    public ?string $lastname = null {
        set => $this->lastname = trim($value);
    }

    /**
     * Fetch all users
     *
     * @return array
     */
    public static function all(): array
    {
        $statement = Database::connection()
            ->prepare("select * from User");

        $statement->execute();

        $rows = $statement->fetchAll();

        $users = [];

        foreach ($rows as $row) {
            $users[] = new self()->fill($row);
        }

        return $users;
    }

    public static function allExceptUser(User $user): array
    {
        $statement = Database::connection()
            ->prepare("select * from User where userId != :userId");

        $statement->execute([':userId' => $user->userId]);

        $rows = $statement->fetchAll();

        $users = [];

        foreach ($rows as $row) {
            $users[] = new self()->fill($row);
        }

        return $users;
    }

    

    /**
     * Try to find an user by its email
     *
     * @param int $email
     * @return User|null
     */
    public static function findByEmail(int $email): User|null
    {
        $statement = Database::connection()
            ->prepare("select * from User where email = :email");

        $statement->execute([
            ':email' => $email
        ]);

        $row = $statement->fetch();

        return $row ? new self()->fill($row) : null;
    }

    /**
     * Get the user fullname
     *
     * @return string
     */
    public function fullname(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * @inheritDoc
     */
    public function insert(): bool
    {
        $statement = Database::connection()
            ->prepare("insert into User (email, password, firstname, lastname) values (:email, :password, :firstname, :lastname)");

        $success = $statement->execute([
            ':email' => $this->email,
            ':password' => $this->password,
            ':firstname' => $this->firstname,
            ':lastname' => $this->lastname,
        ]);

        if ($success) {
            $this->userId = (int)Database::connection()->lastInsertId();
        }

        return $success;
    }

    /**
     * @inheritDoc
     */
    public function update(): bool
    {
        $statement = Database::connection()
            ->prepare("update User set email = :email, password = :password, firstname = :firstname, lastname = :lastname where userId = :userId");

        $success = $statement->execute([
            ':email' => $this->email,
            ':password' => $this->password,
            ':firstname' => $this->firstname,
            ':lastname' => $this->lastname,
            ':userId' => $this->userId,
        ]);

        return $success;
    }
}
