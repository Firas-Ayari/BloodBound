<?php
namespace app\EventSubscriber;

use new\DateTime;
use app\Model\TimestampedInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use App\Entity\Article;


class AdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscriberEvents():array
    {
        return [
            BeforeEntityPersistedEvent::class=>['setEntityCreatedAt'],/*costemise overload related doctrine entity*/
            BeforeEntityUpdatedEvent::class=>['setEntityUpdatedAt']
        ];
    }
    public static function setEntityCreatedAt(BeforeEntityPersistedEvent $event):void
    {
       $entity=$event->getEntityInstance();
       if(!$entity instanceof TimestampedInterface ){
        return;
       }
       $entity->setCreatedAt(new \DATETIME_IMMUTABLE()) ;
    }
    public static function setEntityUpdatedAt(BeforeEntityPersistedEvent $event):void
    {
       $entity=$event->getEntityInstance();
       if(!$entity instanceof TimestampedInterface ){
        return;
       }
       $entity->setUpdatedAt(new \DATETIME_IMMUTABLE());
    }
}