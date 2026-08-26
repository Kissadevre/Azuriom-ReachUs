@once
    @push('styles')
        <style>
            {!! file_get_contents(plugin_path('reachus/assets/css/reachus.css')) !!}
        </style>
    @endpush
@endonce
