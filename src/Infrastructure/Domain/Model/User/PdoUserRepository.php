<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Model\User;

use Antevenio\DddExample\Domain\Model\User\UserNotFoundException;
use PDO;
use Antevenio\DddExample\Domain\Model\User\User;
use Antevenio\DddExample\Domain\Model\User\UserRepository;

class PdoUserRepository implements UserRepository
{

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * PDOUserRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function save(User $user): void
    {
        $sql = 'INSERT INTO example_users (id, email) values (:id, :email)';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $user->getId());
        $stm->bindValue(':email', $user->getEmail());
        $stm->execute();
    }

    public function fetchById($id): User
    {
        $query = 'select id, email from example_users where id = :id';
        $sth = $this->pdo->prepare($query);
        $sth->bindParam(':id', $id);
        $sth->execute();
        $row = $sth->fetch();
        if (!$row) {
            throw new UserNotFoundException();
        }

        return User::fromArray($row);
    }
}
