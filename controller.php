<?php

namespace Concrete\Package\MapStyles;

use Bitter\MapStyles\Provider\ServiceProvider;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected string $pkgHandle = 'map_styles';
    protected string $pkgVersion = '0.0.2';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/MapStyles' => 'Bitter\MapStyles',
    ];

    public function getPackageDescription(): string
    {
        return t('Map Styles is a Concrete CMS add-on that allows you to apply custom Google Maps styles globally on any page.');
    }

    public function getPackageName(): string
    {
        return t('Map Styles');
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        /** @noinspection PhpUnhandledExceptionInspection */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install(): PackageEntity
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}