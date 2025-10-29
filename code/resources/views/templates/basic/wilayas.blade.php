<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>

<select data-live-search="true" data-live-search-style="startsWith" class="selectpicker" name="wilaya[]" multiple>
    {{-- <option value="">@lang('Wilaya')</option> --}}
    @foreach ($wilayas as $w)
        <option value="{{ $w->name }}">
            {!! htmlspecialchars($w->name)!!}
            {{-- {{ __($w->name) }} --}}
        </option>
    @endforeach


</select>
