<?php

namespace app\models;

use fw\core\base\Model;
use Hautelook\Phpass\PasswordHash;
use Intervention\Image\ImageManagerStatic as Image;

class User extends Model
{
    protected $table = 'users';

    public $attributes = [
        'login' => '',
        'password' => '',
        'email' => '',
        'name' => '',
        'birthdate' => '',
        'photo' => ''
    ];

    public $rules = [
        'login' => [
            'rules' => 'required|min_len,6|max_len,100|regex,/^[а-яa-z0-9-.]+$/iu',
            'filters' => 'trim'
        ],
        'password' => [
            'rules' => 'required|min_len,6|max_len,100',
            'filters' => 'trim'
        ],
        'email' => [
            'rules' => 'required|valid_email',
            'filters' => 'trim|lower_case',
        ],
        'name' => [
            'rules' => 'required|min_len,2|max_len,100',
            'filters' => 'trim',
        ],
        'birthdate' => [
            'rules' => 'required|date',
            'filters' => 'trim'
        ]
    ];

    public $uniques = [
        'login' => 'Такой Логин уже существует',
        'email' => 'Такой E-mail уже существует'
    ];

    public $files = ['photo'];

    public function login()
    {
        $login = !empty($_POST['login']) ? trim($_POST['login']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;

        if ($login && $password) {
            $user = \R::findOne($this->table, 'login = ? LIMIT 1', [$login]);

            if ($user) {
                $passwordHasher = new PasswordHash(8, false);

                if ($passwordHasher->CheckPassword($password, $user->password)) {
                    $_SESSION['user'] = [
                        'user_id' => $user->id,
                        'role' => $user->role
                    ];

                    return true;
                }
            }
        }

        $this->errors['auth'] = "Вы ввели некоректные логин или пароль";

        return false;
    }

    public function beforySave(&$data, &$record)
    {
        foreach ($this->files as $photo) {
            if (isset($_FILES[$photo])) {
                $ext = $this->isCorrectMime($_FILES[$photo]['type']);
                if ($ext !== false) {
                    $path = WWW . '/users/';

                    if ($record && !empty($record->$photo)) {
                        if (file_exists($path . $record->$photo)) {
                            unlink($path . $record->$photo);
                        }
                    }

                    $filename = uniqid() . '.' . $ext;

                    Image::make($_FILES[$photo]['tmp_name'])
                        ->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($path . $filename, 90);

                    $record->$photo = $filename;
                }
            }
        }
    }

    public function isCorrectMime($type)
    {
        $correctMime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        ];

        foreach ($correctMime as $mime => $ext) {
            if ($mime == $type) {
                return $ext;
            }
        }

        return false;
    }

    public function calculateAge($birthdate)
    {
        $birthdayTimestamp = strtotime($birthdate);
        $age = date('Y') - date('Y', $birthdayTimestamp);

        if (date('md', $birthdayTimestamp) > date('md')) {
            $age--;
        }

        return $age;
    }

    public function getTextRole($role)
    {
        switch ($role) {
            case 'user':
                $txt = 'Пользователь';
                break;
            case 'admin':
                $txt = 'Администатор';
                break;
            default:
                $txt = 'Другой';
                break;
        }

        return $txt;
    }

    public function isAdult($birthdate)
    {
        if ($this->calculateAge($birthdate) >= 18) {
            return "Совершеннолетний";
        }

        return "Несовершеннолетний";
    }
}
