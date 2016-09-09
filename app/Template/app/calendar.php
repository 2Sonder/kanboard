<a href="/?controller=sonderCalender&action=create&project_id=xx&column_id=xx&swimlane_id=xx" class="popover" title="Add a non project task">Add appointment</a>
<div id="calendar"
     data-check-url="<?= $this->url->href('calendar', 'user', array('user_id' => $user['id'])) ?>"
     data-save-url="<?= $this->url->href('calendar', 'save') ?>"
>
</div>
