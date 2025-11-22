<?php

namespace Repository;

use Database\Database;
use Model\User;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(User $user): User
    {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, ativo) 
                VALUES (:nome, :email, :senha_hash, :ativo)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $user->nome);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':senha_hash', $user->senha_hash);
        $stmt->bindValue(':ativo', $user->ativo);
        
        $stmt->execute();

        $user->id = (int) $this->pdo->lastInsertId();
        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$dados) return null;

        return new User(
            $dados['nome'],
            $dados['email'],
            null,
            $dados['ativo'],
            $dados['id'],
            $dados['senha_hash']
        );
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT id, nome, email, ativo, criado_em FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}