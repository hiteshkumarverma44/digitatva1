<?php

namespace Drupal\event_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Controller for Events listing page.
 */
class EventsListingController extends ControllerBase
{

    /**
     * Renders the events listing page.
     *
     * @return array
     *   A render array representing the events listing page.
     */
    public function listing()
    {
        $query = \Drupal::entityQuery('node')
            ->condition('type', 'events')
            ->condition('status', 1)
            ->accessCheck(TRUE);

        $nids = $query->execute();

        $nodes = Node::loadMultiple($nids);

        $events = [];
        foreach ($nodes as $node) {
            $event = [
                'name' => $node->label(),
                'place' => $node->get('field_event_place')->value,
                'date' => $node->get('field_event_date')->value,
                'added_by' => $node->getOwner()->getDisplayName(),
            ];
            $events[] = $event;
        }

        return [
            '#theme' => 'events_listing_page',
            '#events' => $events,
        ];

        // $build = [
        //     '#theme' => 'events_listing_page',
        //     '#events' => $events,
        //     '#attached' => [
        //         'library' => [
        //             'event_form/events_listing',
        //         ],
        //     ],
        // ];

        // return $build;
    }
}
