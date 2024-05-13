<?php

namespace Drupal\event_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an event form.
 */
class EventForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'event_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['event_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Event name'),
            '#required' => TRUE,
        ];

        $form['event_place'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Event place'),
            '#required' => TRUE,
        ];

        $form['event_date'] = [
            '#type' => 'date',
            '#title' => $this->t('Event date'),
            '#required' => TRUE,
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        // Retrieve form values.
        $event_name = $form_state->getValue('event_name');
        $event_place = $form_state->getValue('event_place');
        $event_date = $form_state->getValue('event_date');

        // Create a new node entity.
        $node = \Drupal\node\Entity\Node::create([
            'type' => 'events',
            'title' => $event_name,
            'field_event_name' => $event_name,
            'field_event_place' => $event_place,
            'field_event_date' => $event_date,
            'field_event_added_by' => \Drupal::currentUser()->id(),
        ]);

        $node->save();
        \Drupal::messenger()->addMessage($this->t('Event data saved successfully.'));


        // Redirect to the events listing page
        $url = \Drupal\Core\Url::fromRoute('event_form.events_listing');
        $response = new RedirectResponse($url->toString());
        $response->send();
    }
}
