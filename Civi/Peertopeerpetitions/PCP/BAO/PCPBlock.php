<?php

namespace Civi\Peertopeerpetitions\PCP\BAO;

class PCPBlock {

  /**
   * Returns an associated array of pcp_block values when given the ID of a
   * survey that should have an associated pcp_block
   *
   * @param $surveyId
   * @return array|null
   */
  public static function getValuesBySurveyId($surveyId) {
    $values = [];

    $params = [
      'entity_id' => $surveyId,
      'entity_table' => 'civicrm_survey'
    ];
    \CRM_Core_DAO::commonRetrieve('CRM_PCP_DAO_PCPBlock', $params, $values);

    return $values;
  }

}
