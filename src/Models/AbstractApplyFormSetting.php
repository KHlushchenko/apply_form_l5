<?php
namespace Vis\ApplyForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

abstract class AbstractApplyFormSetting extends Model
{
    protected $table    = '';
    protected $guarded  = [];

    final public function get(string $slug)
    {
        $value = Cache::tags($this->getTable())->rememberForever($this->getTable() . "_" . $slug, function () use ($slug) {
            return $this->getValue($slug);
        });

        return $value;
    }

    abstract protected function getValue(string $slug);

}
