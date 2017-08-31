<?php
namespace Vis\ApplyForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** Abstract settings class for ApplyForm
 * Class AbstractApplyFormSetting
 * @package Vis\ApplyForm\Models
 */
abstract class AbstractApplyFormSetting extends Model
{
    /** Defines settings table
     * @var string
     */
    protected $table = '';

    /** Defines guarded fields for mass assignment
     * @var array
     */
    protected $guarded = ['slug'];

    /** Gets value from Cache
     * @param string $slug
     * @return mixed
     */
    final public function get(string $slug)
    {
        $value = Cache::tags($this->getTable())->rememberForever($this->getTable() . "_" . $slug, function () use ($slug) {
            return $this->getValue($slug);
        });

        return $value;
    }

    /** Gets value from ApplyFormSetting model
     * @param string $slug
     * @return mixed
     */
    abstract protected function getValue(string $slug);

}
