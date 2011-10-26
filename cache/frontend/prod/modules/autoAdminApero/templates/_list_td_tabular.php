<td class="sf_admin_date sf_admin_list_td_date_at">
  <?php echo false !== strtotime($apero->getDateAt()) ? format_date($apero->getDateAt(), "dd MMMM yyyy") : '&nbsp;' ?>
</td>
<td class="sf_admin_time sf_admin_list_td_time_at">
  <?php echo $apero->getTimeAt() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_location_city">
  <?php echo $apero->getLocationCity() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_location_name">
  <?php echo $apero->getLocationName() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_active">
  <?php echo get_partial('adminApero/list_field_boolean', array('value' => $apero->getIsActive())) ?>
</td>
