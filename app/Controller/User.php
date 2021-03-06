<?php

namespace Kanboard\Controller;

use Kanboard\Notification\Mail as MailNotification;
use Kanboard\Model\Project as ProjectModel;
use Kanboard\Core\Security\Role;

/**
 * User controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class User extends Base
{
    /**
     * List all users
     *
     * @access public
     */
    public function index()
    {
        $paginator = $this->paginator
                ->setUrl('user', 'index')
                ->setMax(30)
                ->setOrder('username')
                ->setQuery($this->user->getQuery())
                ->calculate();

        $this->response->html(
            $this->helper->layout->app('user/index', array(
                'title' => t('Users').' ('.$paginator->getTotal().')',
                'paginator' => $paginator,
            )
        ));
    }

    /**
     * Public user profile
     *
     * @access public
     */
    public function profile()
    {
        $user = $this->user->getById($this->request->getIntegerParam('user_id'));

        if (empty($user)) {
            $this->notfound();
        }

        $this->response->html(
            $this->helper->layout->app('user/profile', array(
                'title' => $user['name'] ?: $user['username'],
                'user' => $user,
            )
        ));
    }

    /**
     * Display a form to create a new user
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
        $is_remote = $this->request->getIntegerParam('remote') == 1 || (isset($values['is_ldap_user']) && $values['is_ldap_user'] == 1);

        $this->response->html($this->helper->layout->app($is_remote ? 'user/create_remote' : 'user/create_local', array(
            'timezones' => $this->timezone->getTimezones(true),
            'languages' => $this->language->getLanguages(true),
            'roles' => $this->role->getApplicationRoles(),
            'projects' => $this->project->getList(),
            'errors' => $errors,
            'values' => $values + array('role' => Role::APP_USER),
            'title' => t('New user')
        )));
    }

    /**
     * Validate and save a new user
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->userValidator->validateCreation($values);

        if ($valid) {
            $project_id = empty($values['project_id']) ? 0 : $values['project_id'];
            unset($values['project_id']);

            $user_id = $this->user->create($values);

            if ($user_id !== false) {
                $this->projectUserRole->addUser($project_id, $user_id, Role::PROJECT_MEMBER);

                if (! empty($values['notifications_enabled'])) {
                    $this->userNotificationType->saveSelectedTypes($user_id, array(MailNotification::TYPE));
                }

                $this->flash->success(t('User created successfully.'));
                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user_id)));
            } else {
                $this->flash->failure(t('Unable to create your user.'));
                $values['project_id'] = $project_id;
            }
        }

        $this->create($values, $errors);
    }

    /**
     * Display user information
     *
     * @access public
     */
    public function show()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user/show', array(
            'user' => $user,
            'timezones' => $this->timezone->getTimezones(true),
            'languages' => $this->language->getLanguages(true),
        )));
    }

    /**
     * Display timesheet
     *
     * @access public
     */
    public function timesheet()
    {
        $user = $this->getUser();

        $subtask_paginator = $this->paginator
            ->setUrl('user', 'timesheet', array('user_id' => $user['id'], 'pagination' => 'subtasks'))
            ->setMax(20)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->subtaskTimeTracking->getUserQuery($user['id']))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->helper->layout->user('user/timesheet', array(
            'subtask_paginator' => $subtask_paginator,
            'user' => $user,
        )));
    }

    /**
     * Display last password reset
     *
     * @access public
     */
    public function passwordReset()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user/password_reset', array(
            'tokens' => $this->passwordReset->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Display last connections
     *
     * @access public
     */
    public function last()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user/last', array(
            'last_logins' => $this->lastLogin->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Display user sessions
     *
     * @access public
     */
    public function sessions()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user/sessions', array(
            'sessions' => $this->rememberMeSession->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Remove a "RememberMe" token
     *
     * @access public
     */
    public function removeSession()
    {
        $this->checkCSRFParam();
        $user = $this->getUser();
        $this->rememberMeSession->remove($this->request->getIntegerParam('id'));
        $this->response->redirect($this->helper->url->to('user', 'sessions', array('user_id' => $user['id'])));
    }

    /**
     * Display user notifications
     *
     * @access public
     */
    public function notifications()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userNotification->saveSettings($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            $this->response->redirect($this->helper->url->to('user', 'notifications', array('user_id' => $user['id'])));
        }

        $this->response->html($this->helper->layout->user('user/notifications', array(
            'projects' => $this->projectUserRole->getProjectsByUser($user['id'], array(ProjectModel::ACTIVE)),
            'notifications' => $this->userNotification->readSettings($user['id']),
            'types' => $this->userNotificationType->getTypes(),
            'filters' => $this->userNotificationFilter->getFilters(),
            'user' => $user,
        )));
    }

    /**
     * Display user integrations
     *
     * @access public
     */
    public function integrations()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userMetadata->save($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            $this->response->redirect($this->helper->url->to('user', 'integrations', array('user_id' => $user['id'])));
        }

        $this->response->html($this->helper->layout->user('user/integrations', array(
            'user' => $user,
            'values' => $this->userMetadata->getAll($user['id']),
        )));
    }

    /**
     * Display external accounts
     *
     * @access public
     */
    public function external()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user/external', array(
            'last_logins' => $this->lastLogin->getAll($user['id']),
            'user' => $user,
        )));
    }

    /**
     * Public access management
     *
     * @access public
     */
    public function share()
    {
        $user = $this->getUser();
        $switch = $this->request->getStringParam('switch');

        if ($switch === 'enable' || $switch === 'disable') {
            $this->checkCSRFParam();

            if ($this->user->{$switch.'PublicAccess'}($user['id'])) {
                $this->flash->success(t('User updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update this user.'));
            }

            $this->response->redirect($this->helper->url->to('user', 'share', array('user_id' => $user['id'])));
        }

        $this->response->html($this->helper->layout->user('user/share', array(
            'user' => $user,
            'title' => t('Public access'),
        )));
    }

    /**
     * Password modification
     *
     * @access public
     */
    public function password()
    {
        $user = $this->getUser();
        $values = array('id' => $user['id']);
        $errors = array();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            list($valid, $errors) = $this->userValidator->validatePasswordModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('Password modified successfully.'));
                } else {
                    $this->flash->failure(t('Unable to change the password.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->helper->layout->user('user/password', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
        )));
    }

    /**
     * Display a form to edit a user
     *
     * @access public
     */
    public function edit()
    {
        $user = $this->getUser();
        $values = $user;
        $errors = array();

        unset($values['password']);

        if ($this->request->isPost()) {
            $values = $this->request->getValues();

            if (! $this->userSession->isAdmin()) {
                if (isset($values['role'])) {
                    unset($values['role']);
                }
            }

            list($valid, $errors) = $this->userValidator->validateModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('User updated successfully.'));
                } else {
                    $this->flash->failure(t('Unable to update your user.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'show', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->helper->layout->user('user/edit', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
            'timezones' => $this->timezone->getTimezones(true),
            'languages' => $this->language->getLanguages(true),
            'roles' => $this->role->getApplicationRoles(),
        )));
    }

    /**
     * Display a form to edit authentication
     *
     * @access public
     */
    public function authentication()
    {
        $user = $this->getUser();
        $values = $user;
        $errors = array();

        unset($values['password']);

        if ($this->request->isPost()) {
            $values = $this->request->getValues() + array('disable_login_form' => 0, 'is_ldap_user' => 0);
            list($valid, $errors) = $this->userValidator->validateModification($values);

            if ($valid) {
                if ($this->user->update($values)) {
                    $this->flash->success(t('User updated successfully.'));
                } else {
                    $this->flash->failure(t('Unable to update your user.'));
                }

                $this->response->redirect($this->helper->url->to('user', 'authentication', array('user_id' => $user['id'])));
            }
        }

        $this->response->html($this->helper->layout->user('user/authentication', array(
            'values' => $values,
            'errors' => $errors,
            'user' => $user,
        )));
    }
}
