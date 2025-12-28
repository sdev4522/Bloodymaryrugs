<?php

namespace Botble\PluginManagement\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\PluginManagement\PluginManifest;
use Composer\Autoload\ClassLoader;

class PluginManagementServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/plugin-management')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadHelpers()
            ->publishAssets();

        $manifest = (new PluginManifest())->getManifest();

        $loader = new ClassLoader();

        foreach ($manifest['namespaces'] as $key => $namespace) {
            $loader->setPsr4($namespace, plugin_path($key . '/src'));
        }

        $loader->register();

        foreach ($manifest['providers'] as $provider) {
            if (! class_exists($provider)) {
                continue;
            }

            $this->app->register($provider);
        }



        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
