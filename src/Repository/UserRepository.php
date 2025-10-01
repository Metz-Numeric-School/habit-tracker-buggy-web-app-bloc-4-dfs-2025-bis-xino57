<?php
namespace App\Repository;

use App\Entity\User;
use App\Utils\EntityMapper;
use Mns\Buggy\Core\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function findAll()
    {
        $users = $this->getConnection()->query("SELECT * FROM mns_user");
        return EntityMapper::mapCollection(User::class, $users->fetchAll());
    }

    public function find(int $id)
    {
        $user = $this->getConnection()->query("SELECT * FROM mns_user WHERE id = $id");
        return EntityMapper::map(User::class, $user);
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM mns_user WHERE email = '$email'";
        $query = $this->getConnection()->query($sql);
        return EntityMapper::map(User::class, $query->fetch());
    }

    public function insert(array $data = array())
    {
        $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO mns_user (lastname, firstname, email, $passwordHash, isadmin) VALUES (:lastname, :firstname, :email, $passwordHash, :isadmin)";
        $query = $this->getConnection()->prepare($sql);
        $query->execute($data);
        return $this->getConnection()->lastInsertId();
    }
}