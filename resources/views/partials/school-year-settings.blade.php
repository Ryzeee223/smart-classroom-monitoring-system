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
                    <select name="school_year" class="form-select">
                        <option value="">Select school year</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                        <option value="2028-2029">2028-2029</option>
                        <option value="2029-2030">2029-2030</option>
                        <option value="2030-2031">2030-2031</option>
                        <option value="2031-2032">2031-2032</option>
                        <option value="2032-2033">2032-2033</option>
                        <option value="2033-2034">2033-2034</option>
                        <option value="2034-2035">2034-2035</option>
                        <option value="2035-2036">2035-2036</option>
                        <option value="2036-2037">2036-2037</option>
                        <option value="2037-2038">2037-2038</option>
                        <option value="2038-2039">2038-2039</option>
                    </select>
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
                <button type="submit" class="btn btn-primary">
                    Save School Year
                </button>
                <span class="text-muted align-self-center">
                    
                </span>
            </div>
        </form>
    </div>
</div>

