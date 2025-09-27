@props(['label', 'options', 'name'])
@vite(['resources/css/components/dropdown.css'])
<div class="select-container">
    <div class="select-container__label">
        <label for="date-range-select">{{ $label }}:</label>
    </div>
    <div class="select-container__select">
        <select required name="{{ $name }}" id="date-range-select">
            @foreach ($options as $option)
                @php
                    $v = \Illuminate\Support\Str::snake($option);
                @endphp
                <option value="{{ $v }}" {{ request()->query($name) === $v ? 'selected' : '' }}>
                    {{ $option }}</option>
            @endforeach
        </select>
        <i class="fa-solid fa-angle-down"></i>
    </div>
</div>
