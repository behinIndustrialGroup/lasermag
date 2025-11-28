<?php

namespace MyFormBuilder\Fields;

class DateField extends AbstractField
{
    public function render(): string
    {
        $name = $this->name;
        $value = $this->attributes['value'] ?? '';
        $s = '<div class="form-group">';
        $s .= '<label>';
        $s .= trans('fields.' . $this->name);
        if($this->attributes['required'] == 'on' && $this->attributes['readonly'] != 'on'){
            $s .= ' <span class="text-danger">*</span>';
        }
        $s .= '</label>';
        $s .= '<input type="text" name="' . $this->name . '" ';

        foreach($this->attributes as $key => $value){
            if($key == 'required'){
                if($value == 'on'){
                    $s .= 'required ';
                }
            }
            elseif($key == 'readonly'){
                if($value == 'on'){
                    $s .= 'readonly ';
                }
            }else{
                $s .= $key . '="' . $value . '" ';
            }
        }
        $s .= '>';
        $s .= "<input type='hidden' name='". $this->name ."_alt' id='". $this->name ."_alt' value='". $this->attributes['altValue'] ."'>";
        $s .= <<<SCRIPT
<script>

    $('#$name').persianDatepicker({
        viewMode: 'day',
        initialValue: false,
        format: 'YYYY-MM-DD',
        initialValueType: 'persian',
        altField: '#{$name}_alt',
        calendar: {
            persian: {
                leapYearMode: 'astronomical',
                locale: 'fa'
            }
        }
    });
</script>
SCRIPT;
        $s .= '</div>';
        return $s;
        if (!isset($this->attributes['type'])) {
            $this->attributes['type'] = 'text';
        }
        return sprintf('<input %s>', $this->buildAttributes());
    }
}
