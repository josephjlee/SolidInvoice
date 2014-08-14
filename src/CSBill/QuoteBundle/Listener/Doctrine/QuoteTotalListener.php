<?php
/**
 * This file is part of CSBill package.
 *
 * (c) 2013-2014 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\QuoteBundle\Listener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use CSBill\QuoteBundle\Entity\Quote;

class QuoteTotalListener
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Quote && count($entity->getUsers()) > 0) {
            $em = $event->getEntityManager();

            $contacts = $em
                ->getRepository('CSBillClientBundle:Contact')
                ->findById(
                    array_map(function ($item) {
                            return $item->getId();
                        },
                        $entity->getUsers()->toArray()
                    )
                );

            $entity->setUsers($contacts);
        }
    }
}
