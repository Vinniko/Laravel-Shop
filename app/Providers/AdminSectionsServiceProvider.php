<?php

namespace App\Providers;

use App\Models\Option;
use App\Models\Product;
use App\Models\Value;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        Product::class => 'App\Admin\Sections\Products',
        Option::class => 'App\Admin\Sections\Options',
        Value::class => 'App\Admin\Sections\Values',
    ];

    /**
     * Register sections.
     *
     * @param Admin $admin
     * @return void
     */
    public function boot(Admin $admin)
    {
    	//

        parent::boot($admin);
    }
}
