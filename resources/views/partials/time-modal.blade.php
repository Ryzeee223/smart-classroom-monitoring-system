{{-- start time and end time modal --}}
<div class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Time</h5>
               <select name="start" id="start">Start time</select>
               
               <select name="end" id="end">End time</select>
            </div>
            <div class="modal-body">
                <form id="timeForm" method="POST" action="#" onsubmit="return false;">
                    @csrf
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>