{{-- School Year settings container (UI-only) --}}
<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">School Year Settings</h4>
    </div>

    <div class="card-body">
        {{--
          UI-only placeholder.
          When backend persistence is added, this form can be wired to a route.
        --}}
        <form method="POST" action="{{ route('settings.store_school_year') }}" onsubmit="return true;">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">School Year</label>
                    <input type="text" name="school_year" class="form-control" placeholder="e.g., 2023-2024">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Select semester</option>
                        <option value="1st sem">1st Semester</option>
                        <option value="2nd sem">2nd Semester</option>
                        <option value="midyear">Mid-Year</option>
                    </select>
                </div>

            <div class="mt-3 d-flex gap-2">
                @php
                    $alreadyExists = isset($semyr) && $semyr->count() > 0;
                @endphp
                <button type="submit" class="btn btn-primary">
                    {{ $alreadyExists ? 'Change School Year' : 'Save School Year' }}
                </button>

                <span class="text-muted align-self-center">
                    
                </span>
            </div>
        </form>
    </div>
</div>

