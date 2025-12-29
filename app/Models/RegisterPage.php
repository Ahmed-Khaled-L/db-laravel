<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterPage extends Model
{
    protected $table = 'register_pages';

    // Composite Primary Key configuration
    protected $primaryKey = ['register_id', 'page_number'];
    public $incrementing = false;

    protected $fillable = ['register_id', 'page_number', 'store_id'];

    // Helpful for composite key saving
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
