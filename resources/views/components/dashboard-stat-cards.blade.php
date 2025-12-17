<div class="row mb-4">
    @foreach ($stats as $stat)
        @php
            // Ensure $stat is an array or object with required keys/properties
            $icon = is_array($stat) ? ($stat['icon'] ?? '📊') : (is_object($stat) ? ($stat->icon ?? '📊') : '📊');
            $title = is_array($stat) ? ($stat['title'] ?? 'No Title') : (is_object($stat) ? ($stat->title ?? 'No Title') : 'No Title');
            $value = is_array($stat) ? ($stat['value'] ?? 0) : (is_object($stat) ? ($stat->value ?? 0) : 0);
        @endphp

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <div class="fs-1 mb-2">{{ $icon }}</div>
                    <h5 class="card-title">{{ $title }}</h5>
                    <p class="card-text fs-4">{{ $value }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
