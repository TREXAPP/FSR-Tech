<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    //custom validations
    'greater_than_date'    => 'Донацијата не смее да биде достапна после рокот на важност на храната.',

    'accepted'             => ':attribute мора да биде прифатено.',
    'active_url'           => ':attribute не е валиден URL.',
    'after'                => ':attribute мора да биде после :date.',
    'after_or_equal'       => ':attribute мора да биде после, или исти со :date.',
    'alpha'                => ':attribute треба да содржи само букви.',
    'alpha_dash'           => ':attribute треба да содржи само букви, бројки или црти.',
    'alpha_num'            => ':attribute треба да содржи само букви или бројки.',
    'array'                => ':attribute мора да биде низа.',
    'before'               => ':attribute мора да биде датум пред :date.',
    'before_or_equal'      => ':attribute мора да биде пред, или исти со :date.',
    'between'              => [
        'numeric' => ':attribute мора да биде помеѓу :min и :max.',
        'file'    => 'The :attribute мора да биде помеѓу :min и :max килобајти.',
        'string'  => 'The :attribute мора да биде помеѓу :min и :max карактери.',
        'array'   => 'The :attribute мора да биде помеѓу :min и :max членови.',
    ],
    'boolean'              => 'Полето мора да биде true или false.',
    'confirmed'            => 'Потврдата за лозинката не е точна.',
    'date'                 => 'Датата не е валидна.',
    'date_format'          => 'Форматот на датата не е точен.',
    'different'            => ':attribute и :other мора да се различни.',
    'digits'               => ':attribute мора да биде :digits цифри.',
    'digits_between'       => ':attribute мора да биде помеѓу :min и :max цифри.',
    'dimensions'           => ':attribute нема валидни димензии на слика.',
    'distinct'             => 'Полето има дупли вредности.',
    'email'                => 'Емаил адресата не е валидна.',
    'exists'               => 'Избраниот :attribute не е валиден.',
    'file'                 => ':attribute мора да е датотека.',
    'filled'               => 'Полето мора да има вредност.',
    'image'                => 'Фајлот мора да биде слика.',
    'in'                   => 'Избраниот :attribute не е валиден.',
    'in_array'             => 'Полето не постои во :other.',
    'integer'              => ':attribute мора да биде цел број.',
    'ip'                   => ':attribute мора да биде валидна IP адреса.',
    'ipv4'                 => ':attribute мора да биде валидна IPv4 адреса.',
    'ipv6'                 => ':attribute мора да биде валидна IPv6 адреса.',
    'json'                 => ':attribute мора да биде валиден JSON стринг.',
    'max'                  => [

        'numeric' => 'Вредноста не смее да биде поголема од :max.',
        'file'    => 'Големината на фајлот не смее да биде поголема од :max килобајти.',
        'string'  => 'Текстот не смее да биде повеќе од :max карактери.',
        'array'   => 'Бројот на членови не смее да биде поголем од :max.',
    ],
    'mimes'                => 'The :attribute мора да биде датотека од тип: :values.',
    'mimetypes'            => 'The :attribute мора да биде датотека од тип: :values.',
    'min'                  => [
        'numeric' => 'The :attribute мора да биде барем :min.',
        'file'    => 'The :attribute мора да биде барем :min килобајти.',
        'string'  => 'Лозинката мора да биде барем :min карактери.',
        'array'   => 'The :attribute мора да има барем :min членови.',
    ],
    'not_in'               => 'Избраниот :attribute не е валиден.',
    'numeric'              => 'The :attribute мора да биде број.',
    'present'              => 'Полето мора да постои.',
    'regex'                => 'Форматот на :attribute не е валиден.',
    'required'             => 'Полето не смее да биде празно.',
    'required_if'          => 'Полето не смее да биде празно кога :other е :value.',
    'required_unless'      => 'Полето не смее да биде празно, освен кога :other е во :values.',
    'required_with'        => 'Полето не смее да биде празно кога :values постои.',
    'required_with_all'    => 'Полето не смее да биде празно кога :values постои.',
    'required_without'     => 'Полето не смее да биде празно кога :values не постои.',
    'required_without_all' => 'Полето не смее да биде празно кога ниедно од :values не постојат.',
    'same'                 => ':attribute и :other мора да се исти.',
    'size'                 => [
        'numeric' => ':attribute мора да биде :size.',
        'file'    => ':attribute мора да биде :size килобајти.',
        'string'  => ':attribute мора да биде :size карактери.',
        'array'   => ':attribute мора да содржи :size членови.',
    ],
    'string'               => ':attribute мора да биде стринг.',
    'timezone'             => ':attribute мора да биде валидна временска зона.',
    'unique'               => ':attribute е зафатено.',
    'uploaded'             => ':attribute не е закачен успешно.',
    'url'                  => 'Форматот на :attribute не е валиден.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
