<?php

namespace Drupal\libro;

use Drupal\Core\Plugin\DefaultPluginManager;

class SaludoPluginManager extends DefaultPluginManager {
  public function __construct(\Traversable $namespaces, \Drupal\Core\Cache\CacheBackendInterface $cache_backend, \Drupal\Core\Extension\ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/Saludo', // Ruta del namespace
      $namespaces,
      $module_handler,
      'Drupal\libro\Plugin\Saludo\SaludoPluginBase', // Clase base
      'Drupal\libro\Annotation\Saludo' // Anotación
    );
    $this->alterInfo('saludo_info');
    $this->setCacheBackend($cache_backend, 'saludo_plugins');
  }
}