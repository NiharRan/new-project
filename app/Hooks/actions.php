<?php

/**
 * All registered action's handlers should be in app\Hooks\Handlers,
 * addAction is similar to add_action and addCustomAction is just a
 * wrapper over add_action which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomAction('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_action('slug-foo', ['FooHandler', 'handleFoo']).
 */

use NewProject\App\Models\User;

/**
 * @var $app WPFluent\Foundation\Application
 */

$app->addAction('admin_menu', 'AdminMenuHandler@add');
$app->addAction('new-project/get_list', 'UserHandler@filterUsers', 10, 2);

$app->addAction("new-project/before_task_create", "TaskHandler@beforeCreate", 10, 1);
$app->addAction("new-project/after_task_create", "TaskHandler@afterCreate", 10, 1);

add_shortcode('new-project-user-list', function ($formId = 'default') {
    $users = User::get();
    $html = '<table class="' . $formId . '">
        <thead>
            <tr>
                <th>Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>  
            </tr>
        </thead>
        <tbody>
            ' . render_users($users) . '
        </tbody>
    </table>';

    return $html;
});

function render_users($users)
{
    $html = '';
    foreach ($users as $user) {
        $html .= "<tr>
            <td>" . date('d-m-Y', strtotime($user->created_at)) . "</td>
            <th>$user->first_name</th>
            <th>$user->last_name</th>
            <th>$user->email</th>
            <th>$user->phone</th>
        </tr>";
    }

    return $html;
}
