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
    'accepted' => 'Het veld :attribute moet worden geaccepteerd.',
    'accepted_if' => 'Het :attribute veld moet worden geaccepteerd wanneer :other :value is.',
    'active_url' => 'Het veld :attribute moet een geldige URL zijn.',
    'after' => 'Het :attribute veld moet een datum zijn na :date.',
    'after_or_equal' => 'Het :attribute veld moet een datum zijn na of gelijk aan :date.',
    'alpha' => 'Het veld :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'Het veld :attribute mag alleen letters, cijfers, streepjes en underscores bevatten.',
    'alpha_num' => 'Het veld :attribute mag alleen letters en cijfers bevatten.',
    'array' => 'Het veld :attribute moet een array zijn.',
    'ascii' => 'Het veld :attribute mag alleen alfanumerieke tekens en symbolen van één byte bevatten.',
    'before' => 'Het :attribute veld moet een datum zijn vóór :date.',
    'before_or_equal' => 'Het :attribute veld moet een datum zijn voor of gelijk aan :date.',
    'between' => [
        'array' => 'Het :attribute veld moet tussen :min en :max items hebben.',
        'file' => 'Het :attribute veld moet tussen :min en :max kilobytes liggen.',
        'numeric' => 'Het :attribute veld moet tussen :min en :max liggen.',
        'string' => 'Het :attribute veld moet tussen :min en :max tekens liggen.',
    ],
    'boolean' => 'Het veld :attribute moet waar of onwaar zijn.',
    'can' => 'Het veld :attribute bevat een niet-geautoriseerde waarde.',
    'confirmed' => 'De bevestiging van het :attribute veld komt niet overeen.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => 'Het veld :attribute moet een geldige datum zijn.',
    'date_equals' => 'Het :attribute veld moet een datum zijn die gelijk is aan :date.',
    'date_format' => 'Het veld :attribute moet overeenkomen met het formaat :format.',
    'decimal' => 'Het :attribute veld moet :decimal decimalen hebben.',
    'declined' => 'Het veld :attribute moet worden geweigerd.',
    'declined_if' => 'Het :attribute veld moet worden geweigerd als :other :value is.',
    'different' => 'Het :attribute veld en :other moeten verschillend zijn.',
    'digits' => 'Het :attribute veld moet :digits cijfers zijn.',
    'digits_between' => 'Het :attribute veld moet tussen :min en :max cijfers liggen.',
    'dimensions' => 'Het veld :attribute heeft ongeldige afbeeldingsafmetingen.',
    'distinct' => 'Het veld :attribute heeft een dubbele waarde.',
    'doesnt_end_with' => 'Het :attributeveld mag niet eindigen met een van de volgende: :values.',
    'doesnt_start_with' => 'Het :attribute veld mag niet beginnen met een van de volgende: :values.',
    'email' => 'Het veld :attribute moet een geldig e-mailadres zijn.',
    'ends_with' => 'Het :attribute veld moet eindigen met een van de volgende: :values.',
    'enum' => 'Het geselecteerde :attribute is ongeldig.',
    'exists' => 'Het geselecteerde :attribute is ongeldig.',
    'extensions' => 'Het :attribute veld moet een van de volgende extensies hebben: :values.',
    'file' => 'Het veld :attribute moet een bestand zijn.',
    'filled' => 'Het veld :attribute moet een waarde hebben.',
    'gt' => [
        'array' => 'Het :attribute veld moet meer dan :value items hebben.',
        'file' => 'Het :attribute veld moet groter zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet groter zijn dan :value.',
        'string' => 'Het :attribute veld moet groter zijn dan :value tekens.',
    ],
    'gte' => [
        'array' => 'Het :attribute veld moet :value items of meer hebben.',
        'file' => 'Het :attribute veld moet groter zijn dan of gelijk aan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet groter of gelijk zijn aan :value.',
        'string' => 'Het :attribute veld moet groter zijn dan of gelijk aan :value tekens.',
    ],
    'hex_color' => 'Het veld :attribute moet een geldige hexadecimale kleur zijn.',
    'image' => 'Het :attribute veld moet een afbeelding zijn.',
    'in' => 'Het geselecteerde :attribute is ongeldig.',
    'in_array' => 'Het :attribute veld moet bestaan in :other.',
    'integer' => 'Het veld :attribute moet een geheel getal zijn.',
    'ip' => 'Het veld :attribute moet een geldig IP-adres zijn.',
    'ipv4' => 'Het veld :attribute moet een geldig IPv4-adres zijn.',
    'ipv6' => 'Het veld :attribute moet een geldig IPv6-adres zijn.',
    'json' => 'Het veld :attribute moet een geldige JSON-string zijn.',
    'lowercase' => 'Het veld :attribute moet kleine letters zijn.',
    'lt' => [
        'array' => 'Het :attribute veld moet minder dan :value items hebben.',
        'file' => 'Het :attribute veld moet kleiner zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet kleiner zijn dan :value.',
        'string' => 'Het :attribute veld moet kleiner zijn dan :value tekens.',
    ],
    'lte' => [
        'array' => 'Het :attribute veld mag niet meer dan :value items hebben.',
        'file' => 'Het :attribute veld moet kleiner zijn dan of gelijk aan :value kilobytes.',
        'numeric' => 'Het :attribute veld moet kleiner dan of gelijk aan :value zijn.',
        'string' => 'Het :attribute veld moet kleiner zijn dan of gelijk aan :value tekens.',
    ],
    'mac_address' => 'Het veld :attribute moet een geldig MAC-adres zijn.',
    'max' => [
        'array' => 'Het :attribute veld mag niet meer dan :max items hebben.',
        'file' => 'Het :attribute veld mag niet groter zijn dan :max kilobytes.',
        'numeric' => 'Het :attribute veld mag niet groter zijn dan :max.',
        'string' => 'Het :attribute veld mag niet groter zijn dan :max tekens.',
    ],
    'max_digits' => 'Het :attribute veld mag niet meer dan :max cijfers hebben.',
    'mimes' => 'Het :attribute veld moet een bestand zijn van het type: :values.',
    'mimetypes' => 'Het :attribute veld moet een bestand zijn van het type: :values.',
    'min' => [
        'array' => 'Het :attribute veld moet ten minste :min items hebben.',
        'file' => 'Het :attribute veld moet minimaal :min kilobytes zijn.',
        'numeric' => 'Het :attribute veld moet minstens :min zijn.',
        'string' => 'Het :attribute veld moet minstens :min tekens bevatten.',
    ],
    'min_digits' => 'Het :attribute veld moet ten minste :min cijfers hebben.',
    'missing' => 'Het veld :attribute moet ontbreken.',
    'missing_if' => 'Het :attribute veld moet ontbreken als :other :value is.',
    'missing_unless' => 'Het :attribute veld moet ontbreken tenzij :other :value is.',
    'missing_with' => 'Het :attribute veld moet ontbreken als :values aanwezig is.',
    'missing_with_all' => 'Het :attribute veld moet ontbreken wanneer :values aanwezig is.',
    'multiple_of' => 'Het :attribute veld moet een veelvoud zijn van :value.',
    'not_in' => 'Het geselecteerde :attribute is ongeldig.',
    'not_regex' => 'De veldindeling van het :attribute is ongeldig.',
    'numeric' => 'Het veld :attribute moet een getal zijn.',
    'password' => [
        'letters' => 'Het veld :attribute moet minstens één letter bevatten.',
        'mixed' => 'Het veld :attribute moet ten minste één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het veld :attribute moet ten minste één getal bevatten.',
        'symbols' => 'Het veld :attribute moet ten minste één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribute is verschenen in een datalek. Kies een ander :attribute.',
    ],
    'present' => 'Het veld :attribute moet aanwezig zijn.',
    'present_if' => 'Het :attribute veld moet aanwezig zijn als :other :value is.',
    'present_unless' => 'Het :attribute veld moet aanwezig zijn tenzij :other :value is.',
    'present_with' => 'Het :attribute veld moet aanwezig zijn als :values aanwezig is.',
    'present_with_all' => 'Het :attribute veld moet aanwezig zijn wanneer :values aanwezig is.',
    'prohibited' => 'Het veld :attribute is verboden.',
    'prohibited_if' => 'Het :attribute veld is verboden als :other :value is.',
    'prohibited_unless' => 'Het :attribute veld is verboden tenzij :other in :values staat.',
    'prohibits' => 'Het :attribute veld verbiedt de aanwezigheid van :other.',
    'regex' => 'Het formaat van het veld :attribute is ongeldig.',
    'required' => 'Het veld :attribute is verplicht.',
    'required_array_keys' => 'Het :attribute veld moet items bevatten voor: :values.',
    'required_if' => 'Het :attribute veld is vereist als :other :value is.',
    'required_if_accepted' => 'Het veld :attribute is vereist als :other is geaccepteerd.',
    'required_unless' => 'Het :attribute veld is verplicht tenzij :other in :values staat.',
    'required_with' => 'Het :attribute veld is vereist als :values aanwezig is.',
    'required_with_all' => 'Het :attribute veld is vereist als :values aanwezig is.',
    'required_without' => 'Het :attribute veld is vereist als :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is vereist als geen van de :values aanwezig is.',
    'same' => 'Het :attribute veld moet overeenkomen met :other.',
    'size' => [
        'array' => 'Het :attribute veld moet :size items bevatten.',
        'file' => 'Het :attribute veld moet :size kilobytes zijn.',
        'numeric' => 'Het :attribute veld moet :size zijn.',
        'string' => 'Het :attribute veld moet bestaan uit :size tekens.',
    ],
    'starts_with' => 'Het :attribute veld moet beginnen met een van de volgende: :values.',
    'string' => 'Het veld :attribute moet een tekenreeks zijn.',
    'timezone' => 'Het veld :attribute moet een geldige tijdzone zijn.',
    'unique' => 'Het :attribute is al gebruikt.',
    'uploaded' => 'Het :attribute is niet geüpload.',
    'uppercase' => 'Het veld :attribute moet in hoofdletters zijn.',
    'url' => 'Het veld :attribute moet een geldige URL zijn.',
    'ulid' => 'Het veld :attribute moet een geldige ULID zijn.',
    'uuid' => 'Het veld :attribute moet een geldige UUID zijn.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
