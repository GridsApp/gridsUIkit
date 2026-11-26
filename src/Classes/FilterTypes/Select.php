<?php

namespace twa\uikit\Classes\FilterTypes;


class Select extends FilterType
{
   
    public $field_type = \twa\uikit\FieldTypes\Select::class;


    public function handle(&$rows , &$joins , $columns ,$table ,  $filter , $filter_value){

        $column = "$table.".$filter['foreign_key'];
        $value1 = $filter_value['value1'];

        if (is_array($value1)) {
            $ids = array_filter($value1, fn($v) => $v !== null && $v !== '');
            if (empty($ids)) {
                return;
            }
            $rows->where(function ($q) use ($column, $ids) {
                foreach ($ids as $id) {
                    $q->orWhere($column, 'LIKE', '%"' . $id . '"%');
                    $q->orWhere($column, 'LIKE', "%'" . $id . "'%");
                }
            });
            return;
        }

        if (!$value1 || $value1 === 'null') {
            return;
        }

        $rows->where(function ($q) use ($column, $value1) {
            $q->where($column, 'LIKE', '%"' . $value1 . '"%');
            $q->orWhere($column, 'LIKE', "%'" . $value1 . "'%");
            $q->orWhere($column, $value1);
        });
    }

}
