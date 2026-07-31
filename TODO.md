# Bug Fix Plan: Foreign Key Constraint Violation on Requests

## Problem
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails - `requests.user_id` references `users.id` but the code is inserting a random number instead of a valid user ID.

## Root Causes Found

### Bug 1: Random user_id used instead of session user_id
**File:** `app/Http/Controllers/RequestController.php`  
**Method:** `storeRequest()`  
**Line:** `'user_id' => $requestId` where `$requestId = random_int(1_000_000, 9_999_999)`  
**Fix:** Change to `'user_id' => $userId` (the actual logged-in user ID from session)

### Bug 2: Wrong foreign key column in Eloquent relationship
**File:** `app/Models/Request.php`  
**Method:** `user()`  
**Line:** `return $this->belongsTo(User::class, 'user_request');`  
**Fix:** Change `'user_request'` to `'user_id'` to match the actual database column

### Bug 3: Wrong join column in dashboard query
**File:** `routes/web.php`  
**Line:** `->join('users', 'users.id', '=', 'requests.user_request')`  
**Fix:** Change `'requests.user_request'` to `'requests.user_id'`

## Steps to Fix
1. [ ] Fix Bug 1: RequestController.php - use $userId instead of random $requestId
2. [ ] Fix Bug 2: Request.php model - correct foreign key in belongsTo relationship
3. [ ] Fix Bug 3: routes/web.php - correct join column in dashboard query

