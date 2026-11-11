@if (session()->has('success') || session()->has('error') || $errors->any())
    @php
        $feedbackIsError = session()->has('error') || $errors->any();
        $feedbackTitle = $errors->any()
            ? 'Please review your information'
            : ($feedbackIsError ? 'Unable to complete request' : 'Success');
        $feedbackMessage = session('error') ?? session('success');
    @endphp
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">{{ $feedbackTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($feedbackMessage)
                        <p class="mb-0">{{ $feedbackMessage }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="mb-0 {{ $feedbackMessage ? 'mt-2' : '' }}">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('feedbackModal')).show();
        });
    </script>
@endif
