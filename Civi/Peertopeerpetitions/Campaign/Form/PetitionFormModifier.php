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
  public static function modify(&$form) {
    self::addFormElements($form);
    //self::setDefaults(&$form);
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
    $profile = array();
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
    $dao->entity_id = $form->getVar('_id');
    $dao->find(TRUE);
    $params['id'] = $dao->id;
    $params['is_active'] = \CRM_Utils_Array::value('pcp_active', $params, FALSE);
    $params['is_approval_needed'] = \CRM_Utils_Array::value('is_approval_needed', $params, FALSE);
    $params['is_tellfriend_enabled'] = 0;

    \CRM_PCP_BAO_PCPBlock::create($params);

  }

}
