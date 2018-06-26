<?php

namespace fw\core\base;

use fw\core\Db;

abstract class Model
{
    protected $pdo;
    protected $table;
    protected $pk = 'id';

    public $attributes = [];
    public $errors = [];
    public $rules = [];
    public $uniques;
    public $files;

    public function __construct()
    {
        $this->pdo = Db::instance();
    }

    public function load($data)
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function validate($data = [], $previous = null)
    {
        if (!count($data)) {
            $data = $this->attributes;
        }

        $validationRules = [];
        $filterRules = [];
        $uniqueRules = null;

        foreach ($this->rules as $key => $validator) {
            if (isset($data[$key])) {
                $validationRules[$key] = $validator['rules'];
                $filterRules[$key] = $validator['filters'];

                if (!empty($this->uniques)) {
                    if (isset($this->uniques[$key])) {
                        $uniqueRules[$key] = $data[$key];
                    }
                }
            }
        }

        $gump = new \GUMP('ru');

        $data = $gump->sanitize($data);

        $gump->validation_rules($validationRules);
        $gump->filter_rules($filterRules);

        $validatedData = $gump->run($data);

        if (!empty($uniqueRules)) {
            $uniqueData = $this->checkUnique($uniqueRules, $previous);
        } else {
            $uniqueData = true;
        }

        if ($validatedData !== false && $uniqueData !== false) {
            return true;
        }

        $this->errors = array_merge($gump->get_errors_array(), $this->errors);

        return false;
    }

    protected function checkUnique($uniqueRules, $previous)
    {
        $result = true;

        foreach ($uniqueRules as $key => $value) {
            $isUniqueField = true;

            $record = \R::findOne($this->table, "$key = ? LIMIT 1", [$value]);

            if ($record) {
                if ($record->$key == $value) {
                    $isUniqueField = false;

                    if (!empty($previous)) {
                        if ($record->$key == $previous->$key) {
                            $isUniqueField = true;
                        }
                    }
                }
            }

            if (!$isUniqueField) {
                $this->errors[] = $this->uniques[$key];
                $result = false;
            }
        }

        return $result;
    }

    public function save($record = null, $data = null, $table = '')
    {
        $table = $table ?: $this->table;

        if (!$record) {
            $tbl = \R::dispense($table);
        } else {
            $tbl = $record;
        }

        if(!$data) {
            $data = $this->attributes;
        }

        foreach ($data as $name => $value) {
            $tbl->$name = $value ?: null;
        }

        $this->beforySave($data, $tbl);

        return \R::store($tbl);
    }

    public function getErrors()
    {
        $errors = '<ul>';

        foreach ($this->errors as $error) {
            $errors .= '<li>' . $error . '</li>';
        }

        $errors .= '</ul>';

        return $errors;
    }

    public function get($id)
    {
        $record = \R::load($this->table, $id);
        return $record;
    }

    public function beforySave(&$data, &$record)
    {
    }

    public function beforyDelete(&$record)
    {
    }

    public function findAll($start = null, $perpage = null, $sort = null, $where = null)
    {
        $sql = null;

        if (!$sort) {
            $sql = "id DESC";
        } else {
            foreach ($sort as $key => $value) {
                if($sql) {
                    $sql .= ', ';
                }

                $sql .= $key . ' ' . $value;
            }
        }

        if (is_numeric($start) && is_numeric($perpage)) {
            $result = \R::findAll($this->table, "$where ORDER BY $sql LIMIT $start, $perpage");
        } else {
            $result = \R::findAll($this->table);
        }

        return $result;
    }

    public function getTotalCount()
    {
        return \R::count($this->table);
    }

    public function delete($obj)
    {
        $this->beforyDelete($obj);

        \R::trash($obj);
    }
}
