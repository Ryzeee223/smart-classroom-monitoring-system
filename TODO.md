# TODO - Leave Requests Access on Dashboard

- [ ] Implement dashboard “Access Status / Leave Requests” panel:
  - Restrict visibility to Dean (role=2) and Assistant Dean (role=3).
  - Show a list of faculty leave requests: only names in a first list/table.
  - Reveal the selected faculty’s request content only when the name is clicked (toggle).

- [ ] Add backend data loading for the leave requests (names + requests) into `dashboard` view.

- [ ] Update `routes/web.php` dashboard route to query leave requests and pass to the view.

- [ ] Update `resources/views/dashboard.blade.php` to render the new UI.

- [ ] Quick sanity checks:
  - No PHP errors in dashboard rendering.
  - Faculty names appear; clicking reveals letter/reason/date.
  - Non-dean roles cannot see the panel (and ideally dashboard itself remains protected).

