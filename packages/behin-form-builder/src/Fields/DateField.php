<?php

namespace MyFormBuilder\Fields;

class DateField extends AbstractField
{
    public function render(): string
    {
        $value = $this->attributes['value'] ?? '';  // مقدار اولیه
        $name = $this->name;

        $s = '<div class="form-group">';
        $s .= '<label>';
        $s .= trans('fields.' . $this->name);

        if (($this->attributes['required'] ?? '') === 'on' && ($this->attributes['readonly'] ?? '') !== 'on') {
            $s .= ' <span class="text-danger">*</span>';
        }

        $s .= '</label>';

        // 🔹 Input اصلی
        $s .= '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';

        foreach ($this->attributes as $key => $val) {
            if ($key == 'required' && $val == 'on') {
                $s .= 'required ';
            } elseif ($key == 'readonly' && $val == 'on') {
                $s .= 'readonly ';
            } elseif ($key !== 'value') {
                $s .= $key . '="' . $val . '" ';
            }
        }

        $s .= '>';

        // 🔹 فیلد ALT
        $s .= "<input type='hidden' name='{$name}_alt' id='{$name}_alt'>";

        // 🔹 اسکریپت: اگر مقدار اولیه دارد → مقدار alt را از آن تولید کن
        $s .= <<<SCRIPT
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                let input = document.getElementById("$name");
                let alt = document.getElementById("{$name}_alt");

                // اگر مقدار اولیه وجود دارد، تبدیل کن به timestamp یا تاریخ میلادی
                if (input.value) {
                    try {
                        let p = new persianDate().parse(input.value);
                        alt.value = p.toDate().getTime(); // ☑ timestamp
                    } catch (e) {
                        console.warn("Invalid Persian date:", input.value);
                    }
                }

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
            });
            </script>
            SCRIPT;

        $s .= '</div>';

        return $s;
    }
}
