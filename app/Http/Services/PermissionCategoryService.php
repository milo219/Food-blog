<?php

namespace App\Http\Services;

use App\Models\PermissionCategory;
use Illuminate\Support\Str;

class PermissionCategoryService
{
    private PermissionCategory $permissionCategory;

    public function __construct(PermissionCategory $permissionCategory)
    {
        $this->permissionCategory = $permissionCategory;
    }

    public function getAll()
    {
        return $this->permissionCategory->all();
    }
    public function getPaginate($perPage)
    {
        return $this->permissionCategory->paginate($perPage);
    }

    public function create($request)
    {
        return $this->permissionCategory->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);
    }

    public function getById($id)
    {
        return $this->permissionCategory->find($id);
    }

    public function update($request, $id)
    {
        $permissionCategory = $this->permissionCategory->find($id);
        $permissionCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);
        $permissions = $permissionCategory->permissions;
        foreach ($permissions as $permission) {
            $permission->update([
                'name' => ucfirst($permission->value).' '.ucfirst($permissionCategory->name),
                'slug' => Str::slug($permission->value . ' ' . $permissionCategory->name),
            ]);
        }
        return $permissionCategory;
    }

}
