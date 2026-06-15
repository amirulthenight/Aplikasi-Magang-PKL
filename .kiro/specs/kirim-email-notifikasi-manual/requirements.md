# Requirements Document

## Introduction

Fitur Kirim Email Notifikasi Manual memungkinkan administrator sistem untuk mengirim email pengingat (reminder) atau teguran kepada karyawan yang meminjam aset IT secara manual melalui tombol di halaman daftar peminjaman. Email akan dikirim berdasarkan status peminjaman dan kedekatan dengan tanggal pengembalian yang direncanakan.

## Glossary

- **System**: Sistem Manajemen Aset IT PT. Bukit Makmur Mandiri Utama
- **Peminjaman_Module**: Modul dalam sistem yang menangani transaksi peminjaman aset IT
- **Email_Sender**: Komponen sistem yang bertanggung jawab mengirim email notifikasi
- **Action_Button**: Tombol pada halaman index peminjaman untuk trigger pengiriman email
- **Reminder_Email**: Email pengingat yang dikirim H-1 atau H-0 sebelum batas waktu pengembalian
- **Warning_Email**: Email teguran yang dikirim saat peminjaman sudah melewati batas waktu pengembalian
- **Administrator**: Pengguna sistem yang memiliki akses untuk mengirim email notifikasi manual
- **Borrower**: Karyawan yang melakukan peminjaman aset IT
- **Loan_Record**: Data transaksi peminjaman yang tersimpan di database
- **Return_Date**: Tanggal yang direncanakan untuk pengembalian aset (tanggal_kembali_rencana)
- **Borrowing_Status**: Status dari transaksi peminjaman (Dipinjam/Kembali/Terlambat)
- **Time_Difference**: Selisih waktu antara hari ini dengan Return_Date

## Requirements

### Requirement 1: Tampilan Tombol Berdasarkan Status Peminjaman

**User Story:** As an Administrator, I want to see action buttons that appear conditionally based on borrowing status, so that I can send appropriate notifications at the right time

#### Acceptance Criteria

1. IF Borrowing_Status is "Kembali", THEN THE Action_Button SHALL be hidden from the interface
2. IF Borrowing_Status is "Dipinjam" AND Time_Difference to Return_Date is greater than 1 day (>24 hours), THEN THE Action_Button SHALL be hidden from the interface
3. IF Borrowing_Status is "Dipinjam" AND Time_Difference to Return_Date is between 0 and 24 hours (inclusive), THEN THE Action_Button SHALL display with background color #FCD34D (yellow-300) and text "Kirim Reminder"
4. IF Borrowing_Status is "Terlambat" OR current datetime is greater than Return_Date, THEN THE Action_Button SHALL display with background color #EF4444 (red-500) and text "Kirim Teguran"
5. THE Action_Button SHALL be positioned in the "Aksi" column of the peminjaman table
6. IF Return_Date field is null OR Borrowing_Status field is null, THEN THE Action_Button SHALL be hidden from the interface
7. THE Time_Difference SHALL be calculated using Carbon::parse(Return_Date)->diffInHours(now()) method

### Requirement 2: Pengiriman Email Reminder

**User Story:** As an Administrator, I want to send reminder emails to borrowers approaching their return deadline, so that they can return assets on time

#### Acceptance Criteria

1. WHEN Administrator clicks Action_Button with text "Kirim Reminder", THE System SHALL retrieve Loan_Record data including Borrower email address
2. WHEN Loan_Record data is retrieved, THE System SHALL send Reminder_Email to Borrower email address within 30 seconds
3. THE Reminder_Email SHALL contain header displaying PT. Bukit Makmur Mandiri Utama company name
4. THE Reminder_Email SHALL include Borrower name, asset name, borrowing date, and Return_Date
5. THE Reminder_Email SHALL contain reminder message indicating the Return_Date
6. IF Loan_Record has missing Borrower email address OR email address format is invalid, THEN THE System SHALL redirect Administrator back to previous page with error message indicating email address is unavailable
7. IF Return_Date is earlier than current date, THEN THE System SHALL redirect Administrator back to previous page with error message indicating loan is already overdue
8. WHEN email is sent successfully, THE System SHALL redirect Administrator back to previous page with success message
9. IF email sending fails after 30 seconds, THEN THE System SHALL redirect Administrator back to previous page with error message

### Requirement 3: Pengiriman Email Teguran

**User Story:** As an Administrator, I want to send warning emails to borrowers who are overdue, so that they are reminded to return assets immediately

#### Acceptance Criteria

1. WHEN Administrator clicks Action_Button with text "Kirim Teguran", THE System SHALL retrieve Loan_Record data including Borrower email address
2. WHEN Loan_Record data is retrieved, THE System SHALL send Warning_Email to Borrower email address within 30 seconds
3. THE Warning_Email SHALL contain header displaying PT. Bukit Makmur Mandiri Utama company name
4. THE Warning_Email SHALL include Borrower name, asset name, borrowing date, and Return_Date
5. THE Warning_Email SHALL contain warning message indicating the return is overdue and immediate action is required
6. IF Loan_Record has missing Borrower email address OR email address format is invalid, THEN THE System SHALL redirect Administrator back to previous page with error message indicating email address is unavailable
7. IF current date is earlier than or equal to Return_Date, THEN THE System SHALL redirect Administrator back to previous page with error message indicating loan is not yet overdue
8. WHEN email is sent successfully, THE System SHALL redirect Administrator back to previous page with success message
9. IF email sending fails after 30 seconds, THEN THE System SHALL redirect Administrator back to previous page with error message
10. THE System SHALL check that Borrower name, asset name, borrowing date, and Return_Date are not null before sending email

### Requirement 4: Route dan Controller Method

**User Story:** As a Developer, I want a dedicated route and controller method for sending notification emails, so that the feature integrates properly with the existing Laravel application

#### Acceptance Criteria

1. THE System SHALL provide a new POST route with pattern /peminjaman/{id}/kirim-notifikasi that accepts Loan_Record ID as parameter
2. THE route SHALL be named peminjaman.kirimNotifikasi
3. THE Peminjaman_Module controller SHALL have a new method named kirimNotifikasi to handle email sending requests
4. WHEN the method receives a request, THE System SHALL validate that Loan_Record with provided ID exists in database
5. IF Loan_Record does not exist, THEN THE System SHALL redirect back with error message "Data peminjaman tidak ditemukan"
6. WHEN Loan_Record exists, THE System SHALL retrieve associated Borrower data including email address
7. IF Borrower email address is null OR empty, THEN THE System SHALL redirect back with error message "Email karyawan tidak tersedia"
8. THE System SHALL determine notification type based on Return_Date: IF current date exceeds Return_Date THEN type is "Warning" ELSE type is "Reminder"
9. THE System SHALL invoke Mail::to() with Borrower email address and PeringatanPeminjamanMail instance
10. THE controller method SHALL return redirect()->back() response with flash message in session

### Requirement 5: Email Template dengan Mailable Class

**User Story:** As a Developer, I want a dedicated Mailable class for loan notification emails, so that email content is consistent and maintainable

#### Acceptance Criteria

1. THE System SHALL provide a Mailable class named PeringatanPeminjamanMail in the app\Mail namespace
2. THE PeringatanPeminjamanMail SHALL accept a Peminjaman model instance and notification type string ("Reminder" or "Warning") as constructor parameters
3. THE PeringatanPeminjamanMail SHALL render email view located at resources/views/emails/peringatan-peminjaman.blade.php with company letterhead displaying "PT. Bukit Makmur Mandiri Utama"
4. THE email view SHALL display Borrower name, asset name, borrowing date, and Return_Date
5. IF notification type is "Reminder", THEN THE email view SHALL display message indicating the return deadline is approaching
6. IF notification type is "Warning", THEN THE email view SHALL display message indicating the return is overdue and immediate action is required
7. IF notification type is "Reminder", THEN THE email subject line SHALL contain the text "Pengingat Pengembalian" and "PT. Bukit Makmur Mandiri Utama"
8. IF notification type is "Warning", THEN THE email subject line SHALL contain the text "Peringatan" or "Teguran" and "PT. Bukit Makmur Mandiri Utama"

### Requirement 6: User Feedback Mechanism

**User Story:** As an Administrator, I want to receive clear feedback after attempting to send an email, so that I know whether the action was successful

#### Acceptance Criteria

1. WHEN email is sent successfully, THE System SHALL display a success flash message in session with key "success"
2. THE success flash message SHALL contain text indicating email was sent successfully to Borrower
3. WHEN email sending fails due to SMTP error OR invalid email format OR authentication failure, THE System SHALL display an error flash message in session with key "error"
4. THE error flash message SHALL include specific reason for failure from the list: SMTP connection error, Invalid email address, Authentication failed, Unknown error
5. THE System SHALL preserve filter parameter selections (status, search) in redirect URL
6. THE System SHALL preserve pagination state (current page number, items per page) in redirect URL
7. THE flash message SHALL be dismissible by user clicking close button or automatically after page refresh
