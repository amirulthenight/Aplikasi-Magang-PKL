# Implementation Plan: Kirim Email Notifikasi Manual

## Overview

Implementasi fitur untuk mengirim email notifikasi manual (reminder/warning) kepada karyawan peminjam aset IT melalui tombol aksi di halaman daftar peminjaman. Fitur ini mencakup conditional button rendering, route dan controller baru, Mailable class, dan handling user feedback dengan flash messages.

## Tasks

- [ ] 1. Create Mailable class for email notifications
  - [ ] 1.1 Create PeringatanPeminjamanMail Mailable class
    - Generate Mailable using `php artisan make:mail PeringatanPeminjamanMail`
    - Add constructor accepting `Peminjaman $peminjaman` and `string $notificationType` parameters
    - Make properties public for view access
    - Implement envelope() method with dynamic subject based on notification type
    - Implement content() method returning view 'emails.peringatan-peminjaman'
    - _Requirements: 5.1, 5.2, 5.7, 5.8_

- [ ] 2. Create email view template
  - [ ] 2.1 Create email template blade file
    - Create file at `resources/views/emails/peringatan-peminjaman.blade.php`
    - Add HTML structure with inline CSS for email client compatibility
    - Include company header with "PT. Bukit Makmur Mandiri Utama" branding
    - Add conditional alert sections for Reminder (yellow) and Warning (red)
    - Display borrower details: name, NIK, asset name, asset code, borrowing date, return date
    - Include conditional action items based on notification type
    - Add professional footer with auto-generated disclaimer
    - Format dates using Carbon's isoFormat() for Indonesian locale
    - _Requirements: 5.3, 5.4, 5.5, 5.6, 2.3, 2.4, 2.5, 3.3, 3.4, 3.5_

- [ ] 3. Add route for manual notification sending
  - [ ] 3.1 Register POST route in web.php
    - Add route inside `auth` and `isAdmin` middleware group
    - Define POST route pattern: `/peminjaman/{id}/kirim-notifikasi`
    - Map to `PeminjamanController::kirimNotifikasi` method
    - Name route as `peminjaman.kirimNotifikasi`
    - _Requirements: 4.1, 4.2_

- [ ] 4. Checkpoint - Verify route registration
  - Run `php artisan route:list | grep kirimNotifikasi` to confirm route exists
  - Ensure all tests pass, ask the user if questions arise

- [ ] 5. Implement controller method for sending notifications
  - [ ] 5.1 Add kirimNotifikasi method to PeminjamanController
    - Add method signature: `public function kirimNotifikasi(int $id): RedirectResponse`
    - Eager load peminjaman with karyawan and barang relations using `Peminjaman::with(['karyawan', 'barang'])->find($id)`
    - Validate peminjaman exists, redirect with error if not found
    - Validate karyawan email exists and is valid using `filter_var($email, FILTER_VALIDATE_EMAIL)`
    - Determine notification type by comparing current date with return date
    - Add additional check for reminder: if more than 24 hours before return date, show error
    - Wrap Mail::to()->send() in try-catch block for exception handling
    - Log exceptions using `\Log::error()` for debugging
    - Return redirect()->back() with appropriate flash message (success or error)
    - Preserve query parameters automatically with redirect()->back()
    - _Requirements: 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9, 4.10, 2.1, 2.2, 2.6, 2.7, 2.8, 2.9, 3.1, 3.2, 3.6, 3.7, 3.8, 3.9, 3.10, 6.1, 6.2, 6.3, 6.4, 6.5, 6.6_

- [ ] 6. Implement conditional button rendering in view
  - [ ] 6.1 Add button logic to peminjaman index view
    - Open `resources/views/peminjaman/index.blade.php`
    - Locate the "Aksi" column in the table foreach loop
    - Add PHP block to calculate status, return date, and time difference
    - Calculate `$diffInHours` using `\Carbon\Carbon::parse($returnDate)->diffInHours(now(), false)`
    - Implement button visibility logic: hide if status is "Kembali" or return date is null or diff > 24 hours
    - Show yellow "Kirim Reminder" button if status is "Dipinjam" and 0 >= diffInHours >= -24
    - Show red "Kirim Teguran" button if status is "Terlambat" or current date exceeds return date
    - Add form with POST action to `route('peminjaman.kirimNotifikasi', $peminjaman->id)`
    - Include CSRF token in form
    - Add JavaScript confirmation dialog with `onsubmit="return confirm(...)"`
    - Apply Tailwind CSS classes for styling: yellow-300/yellow-400 for reminder, red-500/red-600 for warning
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7_

- [ ] 7. Add flash message display to view
  - [ ] 7.1 Add flash message alerts to index view
    - Open `resources/views/peminjaman/index.blade.php`
    - Add success flash message display at the top of the page (after any existing alerts)
    - Check for `session('success')` and display green alert with checkmark icon
    - Add error flash message display at the top of the page
    - Check for `session('error')` and display red alert with error icon
    - Make alerts dismissible with close button
    - Use Tailwind CSS classes for styling: green-100/green-800 for success, red-100/red-800 for error
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.7_

- [ ] 8. Final checkpoint - Integration testing
  - Manually test the complete flow: view button → click → email sent → flash message displayed
  - Verify buttons appear correctly for different loan statuses and time differences
  - Test email sending with valid and invalid email addresses
  - Verify flash messages are displayed correctly
  - Confirm query parameters (filters, pagination) are preserved after redirect
  - Ensure all tests pass, ask the user if questions arise

## Notes

- No optional test tasks included as this is a UI-driven feature with external dependencies (SMTP)
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Feature uses existing database schema - no migrations needed
- Email sending is synchronous (not queued) per requirement of 30-second timeout
- All validation is handled in controller layer with early returns for clean code flow
- Button visibility logic is implemented in view layer using Blade PHP blocks
- Flash messages automatically persist through redirect cycle

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "2.1"] },
    { "id": 1, "tasks": ["3.1"] },
    { "id": 2, "tasks": ["5.1"] },
    { "id": 3, "tasks": ["6.1", "7.1"] }
  ]
}
```
