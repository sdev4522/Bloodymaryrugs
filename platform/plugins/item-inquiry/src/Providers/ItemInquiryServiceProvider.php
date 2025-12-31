<?php

namespace Botble\ItemInquiry\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Base\Facades\DashboardMenu;


class ItemInquiryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'item-inquiry');

        DashboardMenu::registerItem([
            'id'          => 'cms-plugins-item-inquiry',
            'priority'    => 10,
            'parent_id'   => null,
            'name'        => 'Product Inquiries',
            'icon'        => 'fa fa-envelope',
            'url' => '/admin/item-inquiries',
            'permissions' => ['item-inquiry.index'],
        ]);
    }
}
