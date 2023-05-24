<?php

namespace App;
use App\Contracts\AuthInterface;
use App\Contracts\UserInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class Auth implements AuthInterface
{

    protected ?UserInterface $user = null;

    public function __construct(private readonly EntityManager $entityManager)
    {

    }

    public function user(): ?UserInterface
    {
        if($this->user !== null)
        {
            return $this->user;
        }

        $userId = $_SESSION['user'] ?? null;
        if(! $userId)
        {
            return null;
        }
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if(!$user)
        {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    public function attempt(array $credentials):bool
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if(! $user || ! $this->checkCredentials($user,$credentials)){
            return false;
        }
        
        session_regenerate_id();

        $_SESSION['user'] = $user->getId();

        return true;
    }

    public function checkCredentials(UserInterface $user,array $credentials) :bool
    {
        return password_verify($credentials['password'],$user->getPassword());
    }

    public function logOut():void
    {
        unset($_SESSION['user']);

        $this->user = null;
    }
}
