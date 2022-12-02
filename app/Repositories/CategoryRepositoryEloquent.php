<?php

namespace App\Repositories;
use App\Interfaces\Repository\CategoryRepository;
use App\Models\Category;

class CategoryRepositoryEloquent implements CategoryRepository{

    public function insert($data)
    {
        return Category::create($data);
    }

    public function update($id, $data)
    {
        return Category::where('id', $id)->update($data);
    }

    public function getAll()
    {
        return Category::all();
    }

    public function delete($id)
    {
        return Category::where('id', $id)->delete();
    }

}