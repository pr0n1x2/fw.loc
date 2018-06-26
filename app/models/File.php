<?php

namespace app\models;

use fw\core\base\Model;

class File extends Model
{
    protected $table = 'files';
    protected $path = WWW . '/files/';

    public $attributes = [
        'user_id' => '',
        'file' => '',
        'name' => ''
    ];

    public $rules = [
        'user_id' => [
            'rules' => 'required|integer',
            'filters' => 'whole_number'
        ]
    ];

    public $files = ['file'];

    public function beforySave(&$data, &$record)
    {
        foreach ($this->files as $file) {
            if (isset($_FILES[$file])) {
                if ($record && !empty($record->$file)) {
                    if (file_exists($this->path . $record->$file)) {
                        unlink($this->path . $record->$file);
                    }
                }

                $filename = uniqid();

                move_uploaded_file($_FILES[$file]['tmp_name'], $this->path . $filename);

                $record->$file = $filename;
                $record->name = basename($_FILES[$file]['name']);
            }
        }
    }

    public function beforyDelete(&$record)
    {
        if (file_exists($this->path . $record->file)) {
            unlink($this->path . $record->file);
        }
    }

    public function download($file, $name)
    {
        if (file_exists($this->path . $file)) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . (string)(filesize($this->path . $file)));
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header("Content-Transfer-Encoding: binary\n");

            readfile($this->path . $file);
        }
    }
}
