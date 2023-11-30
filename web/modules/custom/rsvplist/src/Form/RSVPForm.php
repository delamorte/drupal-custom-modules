<?php

/**
 * @file
 * A form to collect an email address for RSVP details.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase
{

  public function getFormId(): string
  {
    return 'rsvplist_email_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');

    if (!(is_null($node))) {
      $nid = $node->id();
    } else {
      $nid = 0;
    }

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email address'),
      '#size' => 25,
      '#description' => t('We will send updates to the email address you provide'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('email');
    if(!\Drupal::service('email.validator')->isValid($value)){
      $form_state->setErrorByName('email',
        $this->t('%mail is not a valid email', ['%mail' => $value]));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $submitted_email = $form_state->getValue('email');
    $this->messenger()->addMessage(t('The form is working! You entered @entry',
    ['@entry' => $submitted_email]));
  }
}


