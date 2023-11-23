<?php

namespace App\Dao\Interface;

interface IRepository
{

    public function getAll();

    public function getById(int $id);

    public function create(mixed $data);

    public function update(mixed $data);

    public function delete(int $id);

    public function getBy(string $field, mixed $value);

}
