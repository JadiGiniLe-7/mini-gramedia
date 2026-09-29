<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'color', 'price'])]
class SubscriptionPackage extends Model
{
    public function subscriptionPackageBooks(): HasMany
    {
        return $this->hasMany(SubscriptionPackageBook::class);
    }

    public function subscriptionPackageUsers(): HasMany
    {
        return $this->hasMany(SubscriptionPackageUser::class);
    }
}
