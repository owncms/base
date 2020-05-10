<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\Interfaces\AdminBaseRepositoryInterface;
use Modules\Admin\Entities\Admin;

class AdminRepository implements AdminBaseRepositoryInterface
{

    public function getModel()
    {
        return new Admin;
    }

    public function getAll()
    {
        return $this->getModel()->all();
    }

    public function findById($id)
    {
        return $this->getModel()->whereId($id)->first();
    }

    public function create(...$data)
    {
        return $this->getModel()->create($data);
    }
}
