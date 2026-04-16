@php
    $honeypotField = config('security.honeypot_field', 'company');
    $timestampField = config('security.honeypot_timestamp_field', 'form_started_at');
@endphp

<div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label for="{{ $honeypotField }}">Leave this field empty</label>
    <input id="{{ $honeypotField }}" type="text" name="{{ $honeypotField }}" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="{{ $timestampField }}" value="{{ time() }}">
