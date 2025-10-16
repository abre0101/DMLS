<div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
            <h5 class="card-title text-{{ $color }}">
                <i class="bi {{ $icon }}"></i> {{ $title }}
            </h5>
            <p class="card-text">{{ $text }}</p>
            <a href="{{ route($route) }}" class="btn btn-outline-{{ $color }}">
                {{ $button }}
            </a>
        </div>
    </div>
</div>
