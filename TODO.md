# TODO
- [x] Update College dropdown in `resources/views/users/edit.blade.php` to show values from `college` table
  - Use `college.abbreviation` as `<option value>`
  - Show `college.college_name` as displayed text (included abbreviation in parentheses)
  - Fix selected logic to match the saved value (`$user->course` compared to abbreviation)
- [ ] (After edit) Manually verify dropdown renders expected college rows


