<!-- resources/views/components/modal-form.blade.php -->

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $action }}" method="{{$method}}" id="form">
                    @csrf
                    {{ $slot }}
                    @if($showFooter ?? true) <!-- Check if showFooter is set and true -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
