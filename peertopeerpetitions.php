<?php

require_once 'peertopeerpetitions.civix.php';
use CRM_Peertopeerpetitions_ExtensionUtil as E;
use Civi\Peertopeerpetitions\Campaign\Form\PetitionFormModifier as PetitionFormModifier;

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param mixed $form
 *
 * @throws \HTML_QuickForm_Error
 */
function peertopeerpetitions_civicrm_buildForm($formName, &$form) {
  if (($formName == 'CRM_Campaign_Form_Petition')) {
    /**
     * @var CRM_Campaign_Form_Petition $form
     */

    // add more elements to the form, set default values, etc
    PetitionFormModifier::modify($form);

    // insert a template block in the page
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/PCP/Form/Petition.tpl"
    ));

  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function peertopeerpetitions_civicrm_postProcess($formName, &$form) {
  if (($formName == 'CRM_Campaign_Form_Petition')) {
    /**
     * @var CRM_Campaign_Form_Petition $form
     */
    PetitionFormModifier::postProcess($form);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function peertopeerpetitions_civicrm_config(&$config) {
  _peertopeerpetitions_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function peertopeerpetitions_civicrm_xmlMenu(&$files) {
  _peertopeerpetitions_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function peertopeerpetitions_civicrm_install() {
  _peertopeerpetitions_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function peertopeerpetitions_civicrm_postInstall() {
  _peertopeerpetitions_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function peertopeerpetitions_civicrm_uninstall() {
  _peertopeerpetitions_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function peertopeerpetitions_civicrm_enable() {
  _peertopeerpetitions_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function peertopeerpetitions_civicrm_disable() {
  _peertopeerpetitions_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function peertopeerpetitions_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _peertopeerpetitions_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function peertopeerpetitions_civicrm_managed(&$entities) {
  _peertopeerpetitions_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function peertopeerpetitions_civicrm_caseTypes(&$caseTypes) {
  _peertopeerpetitions_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function peertopeerpetitions_civicrm_angularModules(&$angularModules) {
  _peertopeerpetitions_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function peertopeerpetitions_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _peertopeerpetitions_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function peertopeerpetitions_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function peertopeerpetitions_civicrm_navigationMenu(&$menu) {
  _peertopeerpetitions_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _peertopeerpetitions_civix_navigationMenu($menu);
} // */
