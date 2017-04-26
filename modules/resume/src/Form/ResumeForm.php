<?php
/**
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.
 */
namespace Drupal\resume\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class ResumeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'resume_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
      '#suffix' => '<div id="edit-candidate-name--description"></div>',
    );

    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
      '#suffix' => '<div id="edit-candidate-mail--description"></div>',
    );

    $form['candidate_number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile no'),
      '#suffix' => '<div id="edit-candidate-mobile--description"></div>',
    );

    $form['dependee'] = array(
    '#type' => 'select',
    '#options' => array(
      '_none' => 'Select',
      'a' => 'Option A',
      'b' => 'Option B',
      'c' => 'Option C',
    ),
    //'#multiple' => TRUE,
  );

  $form['dependent'] = array(
    '#type' => 'textfield',
     '#title' => t('dependee'),
    '#states' => array(
       "enabled" => array(
          array("select[name=\"dependee\"]" => array("value" => "a" ))
        ),
    ),
  );


    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type' => 'button',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
      '#ajax' => ['callback' => [$this, 'ajaxCallback']],
    );

    return $form;
  }

  /**
   * AJAX callback.
   */
  public function ajaxCallback($form, FormStateInterface $form_state) {

    /*$item = [
      '#type' => 'item',
      '#title' => $this->t('Ajax value'),
      '#markup' => microtime(),
    ];*/
    $response = new AjaxResponse();
    
    $css = ['border' => '1px solid red'];
    $message = ('<div style="color:red;">Candidate name not valid.</div>');

    $css2 = ['border' => '1px solid red'];
    $message2 = ('<div style="color:red;">Candidate email not valid.</div>');

    $css3 = ['border' => '1px solid red'];
    $message3 = ('<div style="color:red;">Candidate mobile not valid.</div>');

    // Candidate Name
    $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-candidate-name', $css));
    $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#edit-candidate-name--description', $message));


    // Candidate Email
    $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-candidate-mail', $css2));
    $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#edit-candidate-mail--description', $message2));

    // Candidate Mobile
    $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-candidate-number', $css3));
    $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#edit-candidate-mobile--description', $message3));

    //$response->addCommand(new HtmlCommand('#ajax-value', $item));
    return $response;
  }


  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {

      if (strlen($form_state->getValue('candidate_number')) < 10) {
        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
      }

    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

   // drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));

    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

   }
}

?>