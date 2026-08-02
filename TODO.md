# ✅ COMPLETED: Live Classroom Status shows buildings and their rooms

## Goal

The **Live Classroom Status** section on the dashboard should display buildings (as tabs) and their associated rooms.

## Changes

- [x] `routes/web.php` (`/dashboard` route):
  - Added `$buildings = \App\Models\bldg::all();`
  - Added `$rooms = \App\Models\room::with('building')->get();`
  - Passed both `buildings` and `rooms` to `view('dashboard', compact(...))`
- [x] `resources/views/dashboard.blade.php`:
  - Uncommented the `window.__roomsGrid = @json(...)` script so the classroom grid receives the room list (with building abbreviation/name from the `building` relationship)
  - Building tabs now carry `data-building-name` (full building name) in addition to `data-building` (abbreviation)
  - The `.building-title` header now shows the full building name (e.g. "College of Engineering — CC") instead of just "Building CC"
  - `renderForBuilding(abbr, name)` uses the building name for both the header and the "No rooms found" message

## Verified

- `php -l routes/web.php` → no syntax errors
- `php artisan view:cache` → Blade templates cached successfully

---

# ✅ COMPLETED: "Set Schedule" modal blank backdrop fix

## Root cause

The `#setschedmodal` (Set Schedule modal) was **nested inside** `#notificationsModal`. Bootstrap 5 does not properly support nested modals — the nested dialog renders behind the parent modal's backdrop, leaving only a blank dark backdrop visible.

## Fixes applied

- [x] **Moved `#setschedmodal` OUTSIDE `#notificationsModal`** — placed after the parent modal's closing `</div>` in `notifications-modal.blade.php`
- [x] **Changed the "Set Schedule" button** from `data-bs-toggle="modal"` to a `js-open-setsched` class button carrying `data-user-id` and `data-faculty-name`
- [x] **Added JS** that:
  - Reads the clicked button's `data-user-id` / `data-faculty-name`
  - Sets the `#setschedlink` href to `{{ route('schedules') }}?user_id=X`
  - Displays the faculty name in the modal
  - **Closes the notifications modal first**, then opens the Set Schedule modal after the `hidden.bs.modal` event — avoiding the Bootstrap 5 nested-modal stacking/backdrop issue
- [x] **Fixed modal attributes** — `tabindex="-1"`, corrected `aria-labelledby`, removed invalid `arial-labledby`/`arial-hidden`

## Verified

- `php artisan view:cache` → all Blade templates compile successfully
- `dashboard.blade.php` loads `bootstrap.bundle.min.js` (provides `bootstrap.Modal` API used by the script)
- Full flow: click "Set Schedule" in notifications → notifications modal closes → Set Schedule modal opens with correct faculty name → "Set Schedule" button navigates to `/schedules?user_id=X` with faculty pre-selected

---

# ✅ COMPLETED: Simplify the "Set Schedule" modal (no JavaScript)

## Changes

- [x] `notifications-modal.blade.php`:
  - Replaced the JS-dependent `.js-open-setsched` button with a plain `<a href="{{ route('schedules') }}?user_id=...">` link — no JavaScript needed to trigger it.
  - Replaced the JS-populated modal body with a simple, always-visible form containing basic schedule inputs (Faculty, Program, Course, Room, Day, Start Time, End Time) so the user can encode their own inputs.
  - Removed the entire `<script>` block that handled nested-modal opening, link href setting, and faculty-name population.
- [x] `php artisan view:cache` → templates compile successfully.

---

# ✅ COMPLETED: Show faculty according to Dean / Assistant Dean's college

## Goal

The faculty dropdown and assigned schedules list on `schedules.blade.php` should only show users belonging to the same college as the logged-in Dean (role 2) or Assistant Dean (role 3).

## Changes

- [x] `schedulecontroller.php::index()`:
  - Resolves the logged-in user's `college_id` (session → DB fallback)
  - Filters the faculty dropdown by that college for non-admin users (Dean=2, Asst. Dean=3); **no longer depends on `acc_status`**
  - Restricts the schedules list to the same college's users for Dean/Asst. Dean (Program Head sees only their own; Admin sees all)
  - Passes `collegeName` to the view
- [x] `schedules.blade.php`:
  - Shows "College: {name}" badge in the Add Schedule card header
  - Shows "Showing users from: {name}" in the Assigned Faculty Schedules card header

## Verified

- `php artisan view:cache` → templates compile
- `php -l` → no syntax errors
- Tinker: Dean (user 2, college_id 2) sees only CCIT faculty (Ashley, John Paul, Kerby, John) and the college name resolves correctly

