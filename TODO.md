# TODO - Fix Schedule Data Not Storing

## Steps

- [x] 1. Fix `store()` conflict-check query in `schedulecontroller.php` (use `Start_time` / `End_time` keys)
- [x] 2. Fix `store()` insert loop in `schedulecontroller.php` (use `Start_time` / `End_time` keys)
- [x] 3. Fix Edit modal day checkbox values in `schedules.blade.php` (`Mon`–`Fri` instead of full names)
- [x] 4. Fix time format in `schedules.blade.php` from 12-hour (`g:i:s`) to 24-hour (`H:i:s`) to match DB/validation
- [x] 5. Verify fix (PHP syntax check passed)

