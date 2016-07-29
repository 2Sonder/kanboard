<?= $this->render('app/projects', array('paginator' => $project_paginator, 'user' => $user)) ?>
<?= $this->render('app/tasks', array('paginator' => $task_paginator, 'user' => $user)) ?>
<?= $this->render('app/subtasks', array('paginator' => $subtask_paginator, 'user' => $user)) ?>