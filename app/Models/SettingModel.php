<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['setting_key', 'setting_value', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;

    public function getVal($key, $default = null)
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : $default;
    }

    public function setVal($key, $value, $description = null)
    {
        $existing = $this->where('setting_key', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'description'   => $description ?? $existing['description'],
            ]);
        }
        return $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value,
            'description'   => $description,
        ]);
    }
}
