<?php
namespace Vis\ApplyForm\Helpers;

use Vis\Builder\Handlers\CustomHandler;

class TableHandler extends CustomHandler
{
    protected function handleFile($formField, array &$row)
    {
        if ($formField->getFieldName() == 'file') {
            if($row['file']){
                return "<a download href='" . asset($row['file']) . "'>файл</a>";
            }
            return "-";
        }

        return false;
    }

    protected function handleForeign($formField, array &$row)
    {
        if ($formField->getAttribute('type') == 'foreign') {
            if ($item = $formField->getAttribute('foreign_model')::where("id", $row[$formField->getFieldName()])->first()) {
                return "<a target='_blank' href='" . $item->getAdminUrl() . "'>{$item->{$formField->getAttribute('foreign_value_field')}}</a>";
            }
            return "-";
        }
        return false;
    }

    protected function handleValues($formField, array &$row)
    {
        if ($row) {
            if ($file = $this->handleFile($formField, $row)) {
                return $file;
            }

            if ($foreign = $this->handleForeign($formField, $row)) {
                return $foreign;
            }
        }
    }

    public function onGetValue($formField, array &$row, &$postfix)
    {
        return $this->handleValues($formField, $row);
    }

    public function onGetEditInput($formField, array &$row)
    {
        return $this->handleValues($formField, $row);
    }

}
