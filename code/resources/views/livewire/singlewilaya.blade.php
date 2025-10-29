<div>
    <select name="wilaya" class="form-control" required>
        <option value="" selected disabled>{{ __('Select Wilaya') }}</option>
        @foreach($wilayas as $wilaya)
            <option value="{{ $wilaya->code }}">{{ $wilaya->code }} - {{ __($wilaya->name) }}</option>
        @endforeach
    </select>
</div>
