# eMonitor Data Dictionary

This document describes the database schema defined by the Laravel migrations in this repository. Column meanings and domain values are based on migrations, models, controllers, routes, and seeders.

## Scope and Conventions

- Primary keys are Laravel auto-incrementing `id` values unless stated otherwise.
- `created_at` and `updated_at` are added by `$table->timestamps()`.
- `nullable` means the database accepts `NULL`.
- Foreign-key actions below describe the migration definitions.
- Table names preserve the application's existing spelling and casing: `college`, `building`, `room`, `schedule`, and `semyr` are singular; `users`, `programs`, `courses`, and `requests` are plural.

## Entity Relationship Summary

- `college` has many `users`, `building`, `programs`, and `courses`.
- `building` belongs to `college` and has many `room` records.
- `room` belongs to `building` and is referenced by `schedule` and `attendance`.
- `programs` belongs to `college` and is referenced by `schedule`.
- `courses` belongs to `college` and is referenced by `schedule`.
- `users` belongs to `college`, owns schedules, and is referenced by attendance and requests.
- `schedule` belongs to a user, program, room, and course.
- `attendance` belongs to a user, schedule, and room.
- `requests` belongs to a user.

## Application Tables

### `college`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | College identifier. |
| `college_name` | varchar(128) | No | Unique | Full college name. |
| `abbreviation` | varchar(30) | No | Unique | Short college code. |
| `description` | varchar(255) | Yes | - | Optional college description. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `building`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Building identifier. |
| `college_id` | bigint unsigned | Yes | FK -> `college.id` | Owning college; cascade delete. |
| `bldg_name` | varchar(150) | No | Unique | Building name. |
| `bldg_abbr` | varchar(100) | No | Unique | Building abbreviation. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `programs`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Program identifier. |
| `college_id` | bigint unsigned | No | FK -> `college.id` | Owning college; cascade delete. |
| `program_abbr` | varchar(100) | No | Unique | Program abbreviation. |
| `program_name` | varchar(150) | No | Unique | Full program name. |
| `description` | varchar(255) | Yes | - | Optional program description. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `room`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Room identifier. |
| `room_name` | varchar(170) | No | Unique | Room name or room code. |
| `room_type` | varchar(40) | No | - | Room classification, such as laboratory or classroom. |
| `status` | enum | No | `vacant` | Current room availability. Allowed values: `vacant`, `occupied`. |
| `bldg_id` | bigint unsigned | No | FK -> `building.id` | Building containing the room; cascade delete. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `courses`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Course identifier. |
| `college_id` | bigint unsigned | No | FK -> `college.id` | Owning college; cascade delete. |
| `course_code` | varchar(100) | No | Unique | Course code. |
| `course_name` | varchar(150) | No | Unique | Course title. |
| `description` | text | Yes | - | Optional course description. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `users`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | User identifier. |
| `first_name` | varchar(100) | No | - | User first name. |
| `last_name` | varchar(100) | No | - | User last name. |
| `middle_name` | varchar(100) | No | - | User middle name. |
| `employee_ID` | varchar(255) | No | Unique | Employee identifier. |
| `email` | varchar(100) | No | Unique | Login email. |
| `profile_picture` | varchar(255) | Yes | - | Profile image path or filename. |
| `password` | varchar(255) | No | - | Hashed password. |
| `role` | integer | No | - | Application role code. |
| `college_id` | bigint unsigned | No | FK -> `college.id` | User's college; cascade delete. |
| `RFID_code` | varchar(50) | Yes | Unique | Assigned RFID card UID. |
| `acc_status` | varchar(70) | No | `Present` | Current account or attendance-related status used by the application. |
| `email_verified_at` | timestamp | Yes | - | Email verification timestamp. |
| `remember_token` | varchar(100) | Yes | - | Laravel authentication token. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

Role codes currently used by the application:

| Code | Role |
|---:|---|
| 1 | Admin |
| 2 | Dean |
| 3 | Assistant Dean |
| 4 | Faculty |
| 5 | Program Head |

### `schedule`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Schedule identifier. |
| `user_id` | bigint unsigned | No | FK -> `users.id` | Faculty/user assigned to the schedule; cascade delete. |
| `program_id` | bigint unsigned | No | FK -> `programs.id` | Program associated with the class; cascade delete. |
| `room_id` | bigint unsigned | No | FK -> `room.id` | Assigned classroom; cascade delete. |
| `course_id` | bigint unsigned | No | FK -> `courses.id` | Assigned course; cascade delete. |
| `year_level` | varchar(50) | No | - | Year-level label. |
| `section` | varchar(1) | No | - | Section identifier. |
| `day` | varchar(255) | No | - | Scheduled day or day label. |
| `start_time` | time | No | - | Class start time. |
| `end_time` | time | No | - | Class end time. |
| `Semester` | varchar(20) | No | - | Semester label for this schedule. |
| `School_year` | varchar(20) | No | - | School-year label for this schedule. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `semyr`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Semester/school-year record identifier. |
| `semester` | varchar(20) | No | - | Current semester label. |
| `school_year` | varchar(45) | No | - | Current school-year label. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `attendance`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Attendance record identifier. |
| `user_id` | bigint unsigned | No | FK -> `users.id` | User whose attendance is recorded; cascade delete. |
| `schedule_id` | bigint unsigned | No | FK -> `schedule.id` | Schedule being attended; cascade delete. |
| `room_id` | bigint unsigned | No | FK -> `room.id` | Room associated with the attendance record; cascade delete. |
| `time_in` | time | Yes | - | Time of first accepted RFID tap. |
| `time_out` | time | Yes | - | Time of checkout or second accepted tap after class end. |
| `day` | varchar(255) | No | - | Schedule day copied into the attendance record. |
| `attendance_date` | timestamp | No | - | Date for which attendance is recorded. |
| `status` | enum | No | `waiting` | Attendance state. Allowed values: `attended`, `absent`, `late`, `on_leave`, `waiting`. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

Attendance status meanings:

| Status | Meaning |
|---|---|
| `waiting` | Attendance row exists, but no accepted time-in has been recorded. |
| `attended` | RFID time-in was recorded within the first 30 minutes of the class. |
| `late` | RFID time-in was recorded more than 30 minutes after the class start. |
| `absent` | No time-in was recorded by 30 minutes after the class start. |
| `on_leave` | The user's account indicates leave; no normal attendance tap is required. |

### `requests`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | bigint unsigned | No | PK | Request identifier. |
| `user_id` | bigint unsigned | No | FK -> `users.id` | User submitting the request; cascade delete. |
| `letter` | varchar(255) | No | - | Request letter path, filename, or stored reference. |
| `reason` | varchar(50) | No | - | Short reason for the request. |
| `status` | enum | No | `pending` | Request workflow state: `pending`, `approved`, or `declined`. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

## Laravel Framework Tables

### `password_reset_tokens`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `email` | varchar(255) | No | PK | Email associated with the reset request. |
| `token` | varchar(255) | No | - | Password reset token. |
| `created_at` | timestamp | Yes | - | Token creation timestamp. |

### `sessions`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `id` | varchar(255) | No | PK | Session identifier. |
| `user_id` | bigint unsigned | Yes | FK -> `users.id` | Authenticated user; cascade delete. |
| `login_time` | timestamp | Yes | - | Application login timestamp. |
| `logout_time` | timestamp | Yes | - | Application logout timestamp. |
| `user_agent` | varchar(255) | Yes | - | Client user-agent string. |
| `created_at` | timestamp | Yes | - | Creation timestamp. |
| `updated_at` | timestamp | Yes | - | Last update timestamp. |

### `cache`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `key` | varchar(255) | No | PK | Cache key. |
| `value` | mediumtext | No | - | Serialized cache value. |
| `expiration` | integer | No | Indexed | Unix expiration time. |

### `cache_locks`

| Column | Type | Null | Key / Default | Description |
|---|---|---:|---|---|
| `key` | varchar(255) | No | PK | Lock key. |
| `owner` | varchar(255) | No | - | Lock owner token. |
| `expiration` | integer | No | Indexed | Unix expiration time. |

## RFID Attendance Flow

1. The RFID device posts a UID to `POST /api/attendance-scan`.
2. The UID is normalized to uppercase and matched against `users.RFID_code`.
3. The system looks for the user's schedule for the current day and active time window.
4. The system creates or loads the `attendance` row for the user, schedule, and current date.
5. The first valid tap records `time_in` and sets `attended` or `late`.
6. A later tap after the class end records `time_out`.
7. The shared attendance synchronizer changes `waiting` to `absent` after 30 minutes without a time-in.
8. The attendance endpoint also stores the latest scan response in the database cache for dashboard polling.

## Known Schema and Implementation Notes

- The `Device` model targets a `device` table, but no `device` table migration is present in this repository. Its expected columns are `room_id`, `mac_address`, `ip_address`, `status`, and `last_seen`; verify the deployed database before relying on it.
- The courses migration creates `courses`, but its `down()` method attempts to drop `course`. The rollback method should be reviewed before rolling back that migration.
- `attendance_date` is stored as a timestamp even though the attendance logic normally uses a calendar date.
- The database does not define a unique constraint on `(user_id, schedule_id, attendance_date)`, although application code treats that combination as unique with `firstOrCreate`, `updateOrCreate`, and `upsert`.
- The `users` table has both a required foreign key to `college` and a nullable, unique `RFID_code`. A user can exist without an assigned RFID card.
- The table and column naming is inconsistent in places (`employee_ID`, `RFID_code`, `Semester`, `School_year`). New code should preserve these names when querying the existing schema.
