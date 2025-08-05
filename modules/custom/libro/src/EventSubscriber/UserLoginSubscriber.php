<?php

namespace Drupal\libro\EventSubscriber;

use Drupal\libro\Event\UserLoginEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoginSubscriber implements EventSubscriberInterface {
  protected LoggerInterface $logger;

  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public static function getSubscribedEvents(): array {
    return [
      UserLoginEvent::LOGIN => 'onUserLogin',
    ];
  }

  public function onUserLogin(UserLoginEvent $event): void {
    $cuenta = $event->getAccount();

    $nombre = $cuenta->getDisplayName();
    $ip = \Drupal::request()->getClientIp();
    $hora = date('Y-m-d H:i:s');

    $this->logger->info("Usuario $nombre inició sesión desde $ip el $hora.");
  }
}
