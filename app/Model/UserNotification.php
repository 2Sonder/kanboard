<?php

namespace Kanboard\Model;

use Kanboard\Core\Translator;

/**
 * User Notification
 *
 * @package  model
 * @author   Frederic Guillot
 */
class UserNotification extends Base
{
    /**
     * Send notifications to people
     *
     * @access public
     * @param  string $event_name
     * @param  array  $event_data
     */
    public function sendNotifications($event_name, array $event_data)
    {
        $users = $this->getUsersWithNotificationEnabled($event_data['task']['project_id'], $this->userSession->getId());

        foreach ($users as $user) {
            if ($this->userNotificationFilter->shouldReceiveNotification($user, $event_data)) {
                $this->sendUserNotification($user, $event_name, $event_data);
            }
        }
    }

    /**
     * Send notification to someone
     *
     * @access public
     * @param  array     $user        User
     * @param  string    $event_name
     * @param  array     $event_data
     */
    public function sendUserNotification(array $user, $event_name, array $event_data)
    {
        Translator::unload();

        // Use the user language otherwise use the application language (do not use the session language)
        if (! empty($user['language'])) {
            Translator::load($user['language']);
        } else {
            Translator::load($this->config->get('application_language', 'en_US'));
        }

        foreach ($this->userNotificationType->getSelectedTypes($user['id']) as $type) {
            $this->userNotificationType->getType($type)->notifyUser($user, $event_name, $event_data);
        }

        // Restore locales
        $this->language->loadCurrentLanguage();
    }

    /**
     * Get a list of people with notifications enabled
     *
     * @access public
     * @param  integer   $project_id        Project id
     * @param  integer   $exclude_user_id   User id to exclude
     * @return array
     */
    public function getUsersWithNotificationEnabled($project_id, $exclude_user_id = 0)
    {
        if ($this->projectPermission->isEverybodyAllowed($project_id)) {
            return $this->getEverybodyWithNotificationEnabled($exclude_user_id);
        }

        $users = array();
        $members = $this->getProjectUserMembersWithNotificationEnabled($project_id, $exclude_user_id);
        $groups = $this->getProjectGroupMembersWithNotificationEnabled($project_id, $exclude_user_id);

        foreach (array_merge($members, $groups) as $user) {
            if (! isset($users[$user['id']])) {
                $users[$user['id']] = $user;
            }
        }

        return array_values($users);
    }

    /**
     * Enable notification for someone
     *
     * @access public
     * @param  integer $user_id
     * @return boolean
     */
    public function enableNotification($user_id)
    {
        return $this->db->table(User::TABLE)->eq('id', $user_id)->update(array('notifications_enabled' => 1));
    }

    /**
     * Disable notification for someone
     *
     * @access public
     * @param  integer $user_id
     * @return boolean
     */
    public function disableNotification($user_id)
    {
        return $this->db->table(User::TABLE)->eq('id', $user_id)->update(array('notifications_enabled' => 0));
    }

    /**
     * Save settings for the given user
     *
     * @access public
     * @param  integer   $user_id   User id
     * @param  array     $values    Form values
     */
    public function saveSettings($user_id, array $values)
    {
        $types = empty($values['notification_types']) ? array() : array_keys($values['notification_types']);

        if (! empty($types)) {
            $this->enableNotification($user_id);
        } else {
            $this->disableNotification($user_id);
        }

        $filter = empty($values['notifications_filter']) ? UserNotificationFilter::FILTER_BOTH : $values['notifications_filter'];
        $project_ids = empty($values['notification_projects']) ? array() : array_keys($values['notification_projects']);

        $this->userNotificationFilter->saveFilter($user_id, $filter);
        $this->userNotificationFilter->saveSelectedProjects($user_id, $project_ids);
        $this->userNotificationType->saveSelectedTypes($user_id, $types);
    }

    /**
     * Read user settings to display the form
     *
     * @access public
     * @param  integer   $user_id   User id
     * @return array
     */
    public function readSettings($user_id)
    {
        $values = $this->db->table(User::TABLE)->eq('id', $user_id)->columns('notifications_enabled', 'notifications_filter')->findOne();
        $values['notification_types'] = $this->userNotificationType->getSelectedTypes($user_id);
        $values['notification_projects'] = $this->userNotificationFilter->getSelectedProjects($user_id);
        return $values;
    }

    /**
     * Get a list of group members with notification enabled
     *
     * @access private
     * @param  integer   $project_id        Project id
     * @param  integer   $exclude_user_id   User id to exclude
     * @return array
     */
    private function getProjectUserMembersWithNotificationEnabled($project_id, $exclude_user_id)
    {
        return $this->db
            ->table(ProjectUserRole::TABLE)
            ->columns(User::TABLE.'.id', User::TABLE.'.username', User::TABLE.'.name', User::TABLE.'.email', User::TABLE.'.language', User::TABLE.'.notifications_filter')
            ->join(User::TABLE, 'id', 'user_id')
            ->eq('project_id', $project_id)
            ->eq('notifications_enabled', '1')
            ->neq(User::TABLE.'.id', $exclude_user_id)
            ->findAll();
    }

    private function getProjectGroupMembersWithNotificationEnabled($project_id, $exclude_user_id)
    {
        return $this->db
            ->table(ProjectGroupRole::TABLE)
            ->columns(User::TABLE.'.id', User::TABLE.'.username', User::TABLE.'.name', User::TABLE.'.email', User::TABLE.'.language', User::TABLE.'.notifications_filter')
            ->join(GroupMember::TABLE, 'group_id', 'group_id', ProjectGroupRole::TABLE)
            ->join(User::TABLE, 'id', 'user_id', GroupMember::TABLE)
            ->eq(ProjectGroupRole::TABLE.'.project_id', $project_id)
            ->eq(User::TABLE.'.notifications_enabled', '1')
            ->neq(User::TABLE.'.id', $exclude_user_id)
            ->findAll();
    }

    /**
     * Get a list of project members with notification enabled
     *
     * @access private
     * @param  integer   $exclude_user_id   User id to exclude
     * @return array
     */
    private function getEverybodyWithNotificationEnabled($exclude_user_id)
    {
        return $this->db
            ->table(User::TABLE)
            ->columns(User::TABLE.'.id', User::TABLE.'.username', User::TABLE.'.name', User::TABLE.'.email', User::TABLE.'.language', User::TABLE.'.notifications_filter')
            ->eq('notifications_enabled', '1')
            ->neq(User::TABLE.'.id', $exclude_user_id)
            ->findAll();
    }
}
