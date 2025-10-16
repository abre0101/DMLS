<div class="card text-white bg-{{ $color ?? 'primary' }} shadow-sm rounded-3 mb-3">
    <div class="card-body d-flex flex-column align-items-start">
        <div class="d-flex justify-content-between w-100 mb-2">
            
            @if($icon)
                <i class="{{ $icon }} fs-4"></i>
            @endif
        </div>
       
        @if($description)
            <div class="small mt-1">{{ $description }}</div>
        @endif
    </div>
</div>
