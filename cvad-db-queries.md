# LibreBooking DB Tables
| Tables_in_librebooking        |
|-------------------------------|
| accessories                   |
| account_activation            |
| announcement_groups           |
| announcement_resources        |
| announcements                 |
| blackout_instances            |
| blackout_series               |
| blackout_series_resources     |
| credit_log                    |
| custom_attribute_entities     |
| custom_attribute_values       |
| custom_attributes             |
| custom_time_blocks            |
| dbversion                     |
| group_resource_permissions    |
| group_roles                   |
| groups                        |
| layouts                       |
| payment_configuration         |
| payment_gateway_settings      |
| payment_transaction_log       |
| peak_times                    |
| quotas                        |
| refund_transaction_log        |
| reminders                     |
| reservation_accessories       |
| reservation_color_rules       |
| reservation_files             |
| reservation_guests            |
| reservation_instances         |
| reservation_reminders         |
| reservation_resources         |
| reservation_series            |
| reservation_statuses          |
| reservation_types             |
| reservation_users             |
| reservation_waitlist_requests |
| resource_accessories          |
| resource_group_assignment     |
| resource_groups               |
| resource_images               |
| resource_status_reasons       |
| resource_type_assignment      |
| resource_types                |
| resources                     |
| roles                         |
| saved_reports                 |
| schedules                     |
| terms_of_service              |
| time_blocks                   |
| user_email_preferences        |
| user_groups                   |
| user_preferences              |
| user_resource_permissions     |
| user_session                  |
| user_statuses                 |
| users                         |

## CVAD Schedules
| Field                         | Type                  | Null | Key | Default | Extra          |
|-------------------------------|-----------------------|------|-----|---------|----------------|
| schedule_id                   | smallint(5) unsigned  | NO   | PRI | NULL    | auto_increment |
| name                          | varchar(85)           | NO   |     | NULL    |                |
| isdefault                     | tinyint(1) unsigned   | NO   |     | NULL    |                |
| weekdaystart                  | tinyint(2) unsigned   | NO   |     | NULL    |                |
| daysvisible                   | tinyint(2) unsigned   | NO   |     | 7       |                |
| layout_id                     | mediumint(8) unsigned | NO   | MUL | NULL    |                |
| legacyid                      | char(16)              | YES  |     | NULL    |                |
| public_id                     | varchar(20)           | YES  | UNI | NULL    |                |
| allow_calendar_subscription   | tinyint(1)            | NO   |     | 0       |                |
| admin_group_id                | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| start_date                    | datetime              | YES  |     | NULL    |                |
| end_date                      | datetime              | YES  |     | NULL    |                |
| allow_concurrent_bookings     | tinyint(1) unsigned   | NO   |     | 0       |                |
| default_layout                | tinyint(4)            | NO   |     | 0       |                |
| total_concurrent_reservations | smallint(5) unsigned  | NO   |     | 0       |                |
| max_resources_per_reservation | smallint(5) unsigned  | NO   |     | 0       |                |
| additional_properties         | text                  | YES  |     | NULL    |                |
| notes                         | text                  | YES  |     | NULL    |                |
| published                     | tinyint(1) unsigned   | NO   |     | 0       |                |

### Schedules (as of Fall 2025)
```
Photo Documentation
Lighting Studio
New Media
Fabrication Lab
CVAD Student Computer Lab
ART ANNEX - Graduate Project Spaces
Office Conference Rooms
Building Conference Rooms
Critique Spaces
CVAD 4th Floor Roof Deck
CVAD Cora Stafford Lobby Space
CVAD Courtyard
CVAD Van
CVAD Wellness Room
```
```
INSERT INTO schedules (name, isdefault, weekdaystart, layout_id) VALUES
  ('Photo Documentation', 0, 100, 2),
  ('Lighting Studio', 0, 100, 2),
  ('New Media', 0, 100, 2),
  ('Fabrication Lab', 0, 100, 2),
  ('CVAD Student Computer Lab', 0, 100, 2),
  ('Art Annex', 0, 100, 2),
  ('Office Conference Rooms', 0, 100, 2),
  ('Building Conference Rooms', 0, 100, 2),
  ('Critique Spaces', 0, 100, 2),
  ('CVAD 4th Floor Roof Deck', 0, 100, 2),
  ('CVAD Cora Stafford Lobby Space', 0, 100, 2),
  ('CVAD Courtyard', 0, 100, 2),
  ('CVAD Van', 0, 100, 2),
  ('CVAD Wellness Room', 0, 100, 2);

  ```


### CVAD Resources (as of Fall 2025)
| Field                       | Type                  | Null | Key | Default | Extra          |
|-----------------------------|-----------------------|------|-----|---------|----------------|
| resource_id                 | smallint(5) unsigned  | NO   | PRI | NULL    | auto_increment |
| name                        | varchar(85)           | NO   |     | NULL    |                |
| location                    | varchar(255)          | YES  |     | NULL    |                |
| contact_info                | varchar(255)          | YES  |     | NULL    |                |
| description                 | text                  | YES  |     | NULL    |                |
| notes                       | text                  | YES  |     | NULL    |                |
| min_duration                | int(11)               | YES  |     | NULL    |                |
| min_increment               | int(11)               | YES  |     | NULL    |                |
| max_duration                | int(11)               | YES  |     | NULL    |                |
| unit_cost                   | decimal(7,2)          | YES  |     | NULL    |                |
| autoassign                  | tinyint(1) unsigned   | NO   |     | 1       |                |
| requires_approval           | tinyint(1) unsigned   | NO   |     | NULL    |                |
| allow_multiday_reservations | tinyint(1) unsigned   | NO   |     | 1       |                |
| max_participants            | mediumint(8) unsigned | YES  |     | NULL    |                |
| min_notice_time_add         | int(11)               | YES  |     | NULL    |                |
| max_notice_time             | int(11)               | YES  |     | NULL    |                |
| image_name                  | varchar(50)           | YES  |     | NULL    |                |
| schedule_id                 | smallint(5) unsigned  | NO   | MUL | NULL    |                |
| legacyid                    | char(16)              | YES  |     | NULL    |                |
| admin_group_id              | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| public_id                   | varchar(20)           | YES  | UNI | NULL    |                |
| allow_calendar_subscription | tinyint(1)            | NO   |     | 0       |                |
| sort_order                  | smallint(5) unsigned  | YES  |     | NULL    |                |
| resource_type_id            | mediumint(8) unsigned | YES  | MUL | NULL    |                |
| status_id                   | tinyint(3) unsigned   | NO   |     | 1       |                |
| resource_status_reason_id   | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| buffer_time                 | int(10) unsigned      | YES  |     | NULL    |                |
| enable_check_in             | tinyint(1) unsigned   | NO   |     | 0       |                |
| auto_release_minutes        | smallint(5) unsigned  | YES  | MUL | NULL    |                |
| color                       | varchar(10)           | YES  |     | NULL    |                |
| allow_display               | tinyint(1) unsigned   | NO   |     | 0       |                |
| credit_count                | decimal(7,2) unsigned | YES  |     | NULL    |                |
| peak_credit_count           | decimal(7,2) unsigned | YES  |     | NULL    |                |
| min_notice_time_update      | int(11)               | YES  |     | NULL    |                |
| min_notice_time_delete      | int(11)               | YES  |     | NULL    |                |
| date_created                | datetime              | YES  |     | NULL    |                |
| last_modified               | datetime              | YES  |     | NULL    |                |
| additional_properties       | text                  | YES  |     | NULL    |                |


SELECT name, layout_id FROM schedules LIMIT 3 \G;

DELETE FROM schedules WHERE name != 'Default 8-5';