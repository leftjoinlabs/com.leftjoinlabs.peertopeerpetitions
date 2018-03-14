<?php

/**
 * This is a helper class to break out code that needs to be called from hooks
 * within this extension.
 */

namespace Civi\Peertopeerpetitions\Campaign\Form;

class PetitionFormModifier {

  /**
   * @param \CRM_Campaign_Form_Petition $form
   *
   * @throws \HTML_QuickForm_Error
   */
  public static function buildForm(&$form) {
    $acceptableActions = [
      \CRM_Core_Action::ADD,
      \CRM_Core_Action::UPDATE,
      NULL
    ];
    $actionIsAcceptable = in_array($form->_action, $acceptableActions);
    if ($actionIsAcceptable) {
      self::addFormElements($form);
      self::setDefaults($form);

      // insert a template block in the page
      \CRM_Core_Region::instance('page-body')->add(array(
        'template' => "CRM/PCP/Form/Petition.tpl",
      ));
    }
  }

  /**
   * @param \CRM_Campaign_Form_Petition $form
   *
   * @throws \HTML_QuickForm_Error
   */
  protected static function addFormElements(&$form) {
    // Checkbox to enable PCPs
    $form->addElement('checkbox',
      'pcp_active',
      ts('Enable Personal Campaign Pages? (for this petition)'),
      NULL,
      array('onclick' => "return showHideByValue('pcp_active',true,'pcpFields','table-row','radio',false);")
    );

    // Checkbox to make approval required
    $form->addElement('checkbox',
      'is_approval_needed',
      ts('Approval required')
    );

    // Select element to choose a profile
    $form->add('select',
      'supporter_profile_id',
      ts('Supporter Profile'),
      array('' => ts('- select -')) + self::getProfiles($form),
      FALSE
    );

    // Radio buttons for notification setting
    $form->addRadio('owner_notify_id',
      ts('Owner Email Notification'),
      \CRM_Core_OptionGroup::values('pcp_owner_notify'),
      NULL,
      '<br/>',
      FALSE
    );

    // Email address for notifications
    $form->add('text',
      'notify_email',
      ts('Notify Email'),
      \CRM_Core_DAO::getAttribute('CRM_PCP_DAO_PCPBlock', 'notify_email')
    );

    // Text for the link to create new PCPs
    $form->add('text',
      'link_text',
      ts("'Create Personal Campaign Page' link text"),
      \CRM_Core_DAO::getAttribute('CRM_PCP_DAO_PCPBlock', 'link_text')
    );

  }

  /**
   * Adds more default values to the form so that it loads with data in the
   * pcp_block fields if a corresponding pcp_block already exists
   *
   * @param \CRM_Campaign_Form_Petition $form
   *
   * @throws \HTML_QuickForm_Error
   */
  protected static function setDefaults(&$form) {
    $defaults = [];
    if (isset($form->_surveyId)) {
      $params = [
        'entity_id' => $form->_surveyId,
        'entity_table' => 'civicrm_survey'
      ];
      \CRM_Core_DAO::commonRetrieve('CRM_PCP_DAO_PCPBlock', $params, $defaults);
      $defaults['pcp_active'] = \CRM_Utils_Array::value('is_active', $defaults);
    }

    if (empty($defaults['id'])) {
      $defaults['target_entity_type'] = 'event';
      $defaults['is_approval_needed'] = 0;
      $defaults['link_text'] = ts('Promote this survey with a personal campaign page');
      $defaults['owner_notify_id'] = \CRM_Core_OptionGroup::getDefaultValue('pcp_owner_notify');
    }
    $form->setDefaults($defaults);
  }

  /**
   * This function does some magic to retrieve the list of profiles needed for
   * the form element where the user chooses a profile. I copied this code
   * from \CRM_PCP_BAO_PCP::buildPCPForm. I don't understand the difference
   * between $profile and $profiles in this function, but oh well!
   *
   * @param \CRM_Campaign_Form_Petition $form
   *
   * @return array
   */
  protected static function getProfiles($form) {
    $profile = [];
    $isUserRequired = NULL;
    $config = \CRM_Core_Config::singleton();
    if ($config->userFramework != 'Standalone') {
      $isUserRequired = 2;
    }
    \CRM_Core_DAO::commonRetrieveAll(
      'CRM_Core_DAO_UFGroup',
      'is_cms_user',
      $isUserRequired,
      $profiles, array(
        'title',
        'is_active',
      )
    );
    if (!empty($profiles)) {
      foreach ($profiles as $key => $value) {
        if ($value['is_active']) {
          $profile[$key] = $value['title'];
        }
      }
      $form->assign('profile', $profile);
    }
    return $profile;
  }

  /**
   * Save the form values. This code is mostly copied from
   * \CRM_PCP_Form_Contribute::postProcess
   *
   * @param \CRM_Campaign_Form_Petition $form
   */
  public static function postProcess(&$form) {
    // get the submitted form values.
    $params = $form->controller->exportValues($form->getVar('_name'));

    // Source
    $params['entity_table'] = 'civicrm_survey';
    $params['entity_id'] = $form->getVar('_entityId');

    // Target
    $params['target_entity_type'] = 'civicrm_survey';
    $params['target_entity_id'] = $form->getVar('_entityId');

    $dao = new \CRM_PCP_DAO_PCPBlock();
    $dao->entity_table = $params['entity_table'];
    $dao->entity_id = $params['entity_id'];
    $dao->find(TRUE);
    $params['id'] = $dao->id;
    $params['is_active'] = \CRM_Utils_Array::value('pcp_active', $params, FALSE);
    $params['is_approval_needed'] = \CRM_Utils_Array::value('is_approval_needed', $params, FALSE);
    $params['is_tellfriend_enabled'] = 0;

    \CRM_PCP_BAO_PCPBlock::create($params);
  }

  /**
   * Implements hook_civicrm_validateForm().
   *
   * @param array $fields
   * @param \CRM_Campaign_Form_Petition $form
   * @param array $errors
   */
  public static function validate(&$fields, &$form, &$errors) {
    if (!empty($fields['pcp_active']) && $fields['pcp_active'] == "1") {

      // Require a profile to be chosen, and make sure the profile has an email address
      if (empty($fields['supporter_profile_id'])) {
        $errors['supporter_profile_id'] = ts('Supporter profile is a required field.');
      }
      else {
        if (\CRM_PCP_BAO_PCP::checkEmailProfile($fields['supporter_profile_id'])) {
          $errors['supporter_profile_id'] = ts('Profile is not configured with Email address.');
        }
      }

      // Require an owner notification strategy
      if (empty($fields['owner_notify_id'])) {
        $errors['owner_notify_id'] = ts('Owner Email Notification is a required field.');
      }

      // Require a valid notification email addresses
      $emails = \CRM_Utils_Array::value('notify_email', $fields);
      if (!empty($emails)) {
        $emailArray = explode(',', $emails);
        foreach ($emailArray as $email) {
          if ($email && !\CRM_Utils_Rule::email(trim($email))) {
            $errors['notify_email'] = ts('A valid Notify Email address must be specified');
          }
        }
      }

    }
  }

}
