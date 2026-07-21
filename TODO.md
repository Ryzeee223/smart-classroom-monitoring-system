# TODO

- [ ] Update `app/Http/Controllers/schedulecontroller.php` to insert required foreign keys (`program_id`, `course_id`, `room_id`) into `schedule`.
- [ ] Fix `schedulecontroller@update()` to update the correct DB columns and convert `Course`/`Room` to FK IDs.
- [ ] Fix `schedulecontroller@index()` to pass the correct `$courses` variable (currently mismatch: uses `course` vs `$courses`).
- [ ] Run a quick migration/code check and verify schedule creation no longer throws `program_id` default error.

