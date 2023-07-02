<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CategoryService
{
    public function __construct(private readonly EntityManager $entityManager)
    {

    }

    public function create(String $name,User $user) :Category
    {
        $category = new Category();

        $category->setName($name);
        $category->setUser($user);

        $this->entityManager->persist($category);
        $this->entityManager->flush(); //to make flush after process with db
        
        return $category;
    }

    public function getAll() :array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function delete(int $id):void
    {
        $category = $this->entityManager->find(Category::class,$id);

        $this->entityManager->remove($category);
        $this->entityManager->flush(); //to make flush after process with db

    }

    public function get(int $id):?Category
    {
        return $this->entityManager->find(Category::class,$id);
    }

}
