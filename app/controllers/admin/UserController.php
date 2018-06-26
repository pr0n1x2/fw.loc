<?php

namespace app\controllers\admin;

use app\models\User;
use fw\core\base\View;
use fw\libs\Pagination;
use Hautelook\Phpass\PasswordHash;

class UserController extends AppController
{
    public function indexAction()
    {
        $this->isAdmin();

        $model = new User();

        $totalCount = $model->getTotalCount();

        if (!empty($_GET['sort'])) {
            $sort = ['birthdate' => $_GET['sort']];

            if ($_GET['sort'] == 'asc') {
                $sortParam = 'desc';
            } else {
                $sortParam = 'asc';
            }
        } else {
            $sort = null;
            $sortParam = 'desc';
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 10;

        $pagination = new Pagination($page, $totalCount, $perpage);
        $start = $pagination->getStart();

        $users = $model->findAll($start, $perpage, $sort);

        if ($users) {
            foreach ($users as $user) {
                $user->age = $model->calculateAge($user->birthdate);
                $user->role = $model->getTextRole($user->role);
                $user->adult = $model->isAdult($user->birthdate);
            }
        }

        $this->set(compact('users', 'pagination', 'sortParam'));

        View::setMeta('Список пользователей');
    }

    public function profileAction()
    {
        $users = new User();
        $user = $users->get($_SESSION['user']['user_id']);
        $data = $_POST;

        if (!empty($data)) {
            $users->load($data);

            if ($users->validate($data, $user)) {
                if ($users->save($user, $data)) {
                    View::setMessage("Данные успешно сохранены");
                    redirect();
                }
            } else {
                setPrevValue($user, $data);
                View::setMessage($users->getErrors(), false);
            }
        }

        $this->set(compact('user'));

        View::setMeta('Мой профиль');
    }

    public function passwordAction()
    {
        $users = new User();
        $user = $users->get($_SESSION['user']['user_id']);
        $data = $_POST;

        if (!empty($data)) {
            $users->load($data);

            if ($users->validate($data, $user)) {
                $passwordHasher = new PasswordHash(8, false);
                $data['password'] = $passwordHasher->HashPassword($users->attributes['password']);

                if ($users->save($user, $data)) {
                    View::setMessage("Пароль успешно изменен");
                    redirect();
                }
            } else {
                View::setMessage($users->getErrors(), false);
            }
        }

        $this->set(compact('user'));

        View::setMeta('Изменить пароль');
    }

    public function addAction()
    {
        $this->isAdmin();

        if (!empty($_POST)) {
            $user = new User();
            $user->load($_POST);

            if ($user->validate()) {
                $passwordHasher = new PasswordHash(8, false);
                $user->attributes['password'] = $passwordHasher->HashPassword($user->attributes['password']);

                if ($user->save()) {
                    View::setMessage("Новый пользователь успешно добавлен");
                    redirect('/admin/user/');
                }
            } else {
                View::setMessage($user->getErrors(), false);
            }
        }

        View::setMeta('Добавить пользователя');
    }

    public function editAction()
    {
        $this->isAdmin();

        $users = new User();
        $user = $users->get($_GET['id']);
        $data = $_POST;

        if (!empty($data)) {
            $users->load($data);

            if ($users->validate($data, $user)) {
                if ($users->save($user, $data)) {
                    View::setMessage("Данные успешно сохранены");
                    redirect();
                }
            } else {
                setPrevValue($user, $data);
                View::setMessage($users->getErrors(), false);
            }
        }

        $this->set(compact('user'));

        View::setMeta('Редактировать пользователя');
    }
}
