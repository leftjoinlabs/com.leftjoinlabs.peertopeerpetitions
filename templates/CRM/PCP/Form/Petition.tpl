
<div id="pcpBlock" class="crm-accordion-wrapper open">

  <div class="crm-accordion-header">
    Personal Campaign Pages
  </div>

  <div class="crm-accordion-body">
    <div class="crm-block crm-form-block crm-form-title-here-form-block">

      <table class="form-layout">
        <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_is_active">
          <td class="label">&nbsp;</td>
          <td>{$form.pcp_block_is_active.html} {$form.pcp_block_is_active.label}</td>
        </tr>
      </table>

      <div class="spacer"></div>

      <div id="pcpFields">
        {crmRegion name="pcp-form-pcp-fields"}
          <table class="form-layout">
            <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_is_approval_needed">
              <td class="label">{$form.pcp_block_is_approval_needed.label}</td>
              <td>{$form.pcp_block_is_approval_needed.html}</td>
            </tr>
            <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_notify_email">
              <td class="label">{$form.pcp_block_notify_email.label}</td>
              <td>{$form.pcp_block_notify_email.html}</td>
            </tr>
            <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_supporter_profile_id">
              <td class="label">{$form.pcp_block_supporter_profile_id.label}</td>
              <td>{$form.pcp_block_supporter_profile_id.html}</td>
            </tr>
            <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_owner_notify_id">
              <td class="label">{$form.pcp_block_owner_notify_id.label}</td>
              <td>{$form.pcp_block_owner_notify_id.html}</td>
            </tr>
            <tr class="crm-contribution-contributionpage-pcp-form-block-pcp_block_link_text">
              <td class="label">{$form.pcp_block_link_text.label}</td>
              <td>
                {$form.pcp_block_link_text.html|crmAddClass:huge}<br/>
                <span class="description">
              {if $config->userSystem->is_drupal || $config->userFramework EQ 'WordPress'}
                {ts}You can also place additional links (or menu items) allowing constituents to create their own fundraising pages using the following URL:{/ts}
                <br/>
                <em>{crmURL a=1 fe=1 p='civicrm/contribute/campaign' q="action=add&reset=1&pageId=999&component=survey"}</em>

<!-- TODO fix 999 -->

  {elseif $config->userFramework EQ 'Joomla'}
                {ts}You can also create front-end links (or menu items) allowing constituents to create their own fundraising pages using the Menu Manager. Select
                <strong>Contributions &raquo; Personal Campaign Pages</strong>
                and then select this event.{/ts}
              {/if}
            </span>
              </td>
            </tr>
          </table>
        {/crmRegion}
      </div>

    </div>
  </div>


</div>

{include file="CRM/common/showHideByFieldValue.tpl"
trigger_field_id    = "pcp_block_is_active"
trigger_value       = "true"
target_element_id   = "pcpFields"
target_element_type = "block"
field_type          = "radio"
invert              = "false"
}

{* reposition the block *}
<script type="text/javascript">
  cj('#pcpBlock').insertAfter('.crm-campaign-survey-form-block .form-layout')
</script>
