<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\EmailType;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\IdType;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\RoleType;
use App\Auth\Service\TokenizerFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $configurator->parameters()->set('auth.token_ttl', 'PT1H');

    $configurator->extension('doctrine', [
        'dbal' => [
            'types' => [
                IdType::NAME => Id::class,
                RoleType::NAME => Role::class,
                EmailType::NAME => Email::class,
            ],
        ],
        'orm' => [
            'mappings' => [
                'Auth' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => __DIR__ . '/Entity',
                    'prefix' => 'App\Auth\Entity',
                    'alias' => 'Auth',
                ],
            ],
        ],
    ]);

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Auth\\', '.')->exclude([
        './config.php',
        './Command',
        './Entity',
        './Query',
        './Test',
        './Validator/Constraint',
    ]);

    $services->load('App\\Auth\\Command\\', './Command/**/Handler.php');
    $services->load('App\\Auth\\Entity\\', './Entity/**/*Repository.php');
    $services->load('App\\Auth\\Query\\', './Query/**/Handler.php');

    $services->set(Service\Tokenizer::class)
        ->factory(service(TokenizerFactory::class))
        ->arg('$interval', '%auth.token_ttl%');
};
