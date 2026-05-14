<?php

namespace Database\Seeders;

use App\Models\ActionType;
use App\Models\ConfiscatedAsset;
use App\Models\Employee;
use App\Models\FollowUpAction;
use App\Models\IncidentReport;
use App\Models\PenaltyAssignment;
use App\Models\PenaltyReceipt;
use App\Models\PenaltySchedule;
use App\Models\PenaltyType;
use App\Models\SubCity;
use App\Models\User;
use App\Models\ViolationRecord;
use App\Models\ViolationType;
use App\Models\Violator;
use App\Models\WarningLetter;
use App\Models\Woreda;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenaltyModuleSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // LOOKUP TABLES — always seeded regardless of scenario users
        // ============================================================
        $penaltyTypes = [];
        foreach ([
            ['name' => 'Written Warning',  'default_duration_days' => 30,   'description' => 'Formal written warning'],
            ['name' => 'Suspension',        'default_duration_days' => 14,   'description' => 'Temporary suspension'],
            ['name' => 'Demotion',          'default_duration_days' => 180,  'description' => 'Reduction in rank'],
            ['name' => 'Dismissal',         'default_duration_days' => null, 'description' => 'Termination'],
            ['name' => 'Fine Deduction',    'default_duration_days' => 1,    'description' => 'Salary deduction'],
        ] as $pt) {
            $penaltyTypes[] = PenaltyType::firstOrCreate(['name' => $pt['name']], $pt + ['is_active' => true]);
        }

        $actionTypes = [];
        foreach ([
            ['name' => 'Verbal Counseling',    'description' => 'Informal discussion'],
            ['name' => 'Written Notice',        'description' => 'Formal notice'],
            ['name' => 'Asset Seizure',         'description' => 'Confiscation'],
            ['name' => 'Court Referral',        'description' => 'Court system referral'],
            ['name' => 'Task Force Referral',   'description' => 'Task force escalation'],
            ['name' => 'Follow-up Inspection',  'description' => 'Re-inspection'],
        ] as $at) {
            $actionTypes[] = ActionType::firstOrCreate(['name' => $at['name']], $at + ['is_active' => true]);
        }

        // ============================================================
        // PENALTY SCHEDULES & VIOLATION TYPES
        // Source: Reg. 150/2023 (Schedules 1-6) + Reg. 180/2024 (Schedule 7)
        // Administrative process: Art.15 — pay within 3 consecutive working days;
        //   non-payment → double fine; refuses double → court (Art.16)
        //   Recurrent: 2nd same offense → double; still fails → court
        //   Appeal: 2 working days to Woreda; Woreda decides in 5 days (Art.18)
        // ============================================================
        $vtIds = [];
        $schedules = [

            // ---- SCHEDULE 1 — ህገ-ወጥ መሬት ወረራ እና ህገ-ወጥ ግንባታ ----
            [
                'name_am'     => 'ሰንጠረዥ 1 — ህገ-ወጥ መሬት ወረራ እና ህገ-ወጥ ግንባታ',
                'name_en'     => 'Schedule 1 — Illegal Land Seizure and Construction',
                'level'       => 1,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S1-001', 'name_am' => 'ህገ-ወጥ ይዞታ/ወረራ',                       'name_en' => 'Illegal land possession/seizure',                    'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 0.00],
                    ['code' => 'S1-002', 'name_am' => 'መሬት ማስፋፋት',                             'name_en' => 'Land expansion (stop + demolish)',                   'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 0.00],
                    ['code' => 'S1-003', 'name_am' => 'ያለፈቃድ ግንባታ (ፕላስቲክ/ካርቫስ)',             'name_en' => 'Unauthorized construction (plastic/canvas)',          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 1000.00],
                    ['code' => 'S1-004', 'name_am' => 'ያለፈቃድ ግንባታ (ጡብ/ብረት/ድንጋይ)',           'name_en' => 'Unauthorized construction (brick/metal/stone)',       'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 3000.00],
                    ['code' => 'S1-005', 'name_am' => 'ያለፈቃድ ማሻሻያ (ፈቃድ ባለው መሬት)',           'name_en' => 'Renovation without permit (permitted land)',          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 2000.00],
                    ['code' => 'S1-006', 'name_am' => 'ያለፈቃድ ዕድሳት (ቁሳቁስ/ንድፍ ለውጥ የለም)',     'name_en' => 'Renewal without material/design change (no permit)',  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 1', 'fine_amount' => 300.00],
                ],
            ],

            // ---- SCHEDULE 2 — ህገ-ወጥ ጠጣር/ፍሳሽ ቆሻሻ ማስወገድ ----
            [
                'name_am'     => 'ሰንጠረዥ 2 — ህገ-ወጥ ጠጣር/ፍሳሽ ቆሻሻ ማስወገድ',
                'name_en'     => 'Schedule 2 — Illegal Solid/Sewage Waste Disposal',
                'level'       => 2,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S2-001', 'name_am' => 'ቤተሰብ ያልተሻለ ቆሻሻ ማስወገድ',                 'name_en' => 'Household improper waste disposal',                   'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 500.00],
                    ['code' => 'S2-002', 'name_am' => 'ድርጅት ያልተሻለ ቆሻሻ ማስወገድ',                 'name_en' => 'Organization improper waste disposal',                'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 3000.00],
                    ['code' => 'S2-003', 'name_am' => 'ኢንዱስትሪ ያልተፈቀደ ቆሻሻ',                    'name_en' => 'Industrial unauthorized waste',                       'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 10000.00],
                    ['code' => 'S2-004', 'name_am' => 'የጤና ተቋም ቆሻሻ',                           'name_en' => 'Health institution waste',                            'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 15000.00],
                    ['code' => 'S2-005', 'name_am' => 'ሌሎች ድርጅቶች ቆሻሻ',                        'name_en' => 'Other organizations waste',                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-006', 'name_am' => 'የመኪና ቆሻሻ',                              'name_en' => 'Car waste',                                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-007', 'name_am' => 'ከመኪና ቆሻሻ መጣል (ተሳፋሪ)',                  'name_en' => 'Passenger throwing waste from car',                   'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 200.00],
                    ['code' => 'S2-008', 'name_am' => 'ቆሻሻ መጣል',                               'name_en' => 'Littering',                                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 200.00],
                    ['code' => 'S2-009', 'name_am' => 'ያለፈቃድ አሸዋ መጣል',                         'name_en' => 'Sand dumping without permit',                         'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-010', 'name_am' => 'ያልተሸፈነ ቆሻሻ ትራንስፖርት',                   'name_en' => 'Uncovered waste transport',                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-011', 'name_am' => 'ጎዳና ላይ ሽንት ቤት',                         'name_en' => 'Toilet outside on road',                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 200.00],
                    ['code' => 'S2-012', 'name_am' => 'የጎዳና ቆሻሻ ቦክስ ማፍረስ',                    'name_en' => 'Destroying roadside waste bins',                      'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 20000.00],
                    ['code' => 'S2-013', 'name_am' => 'ቆሻሻ ቦክስ ላይ ማስታወቂያ',                    'name_en' => 'Posting advertisements on waste bins',                'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 500.00],
                    ['code' => 'S2-014', 'name_am' => 'አገልግሎት ድርጅት ቢን ማጣት',                   'name_en' => 'Service organization missing waste bin',              'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-015', 'name_am' => 'ያልተፈቀደ የእንስሳ ቆሻሻ',                     'name_en' => 'Unauthorized animal waste',                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-016', 'name_am' => 'የእንስሳ ዳፊቃ/ቡቃዳ (ለነፍስ)',                 'name_en' => 'Animal defecation in public (per head)',              'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 200.00],
                    ['code' => 'S2-017', 'name_am' => 'ዝግጅት አዘጋጅ አለማፅዳት',                    'name_en' => 'Event organizer not cleaning up',                     'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 5000.00],
                    ['code' => 'S2-018', 'name_am' => 'ሃይማኖታዊ በዓል አለማፅዳት',                   'name_en' => 'Religious holiday cleanup violation',                 'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 3000.00],
                    ['code' => 'S2-019', 'name_am' => 'ታክሲ/ገበያ 5ሜ. አለማፅዳት',                   'name_en' => 'Taxi/market not cleaning 5m radius',                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 500.00],
                    ['code' => 'S2-020', 'name_am' => 'ባቡር አካባቢ አለማፅዳት',                      'name_en' => 'Railway cleaning violation',                          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 5000.00],
                    ['code' => 'S2-021', 'name_am' => 'ተቋም ማፅዳት ዘመቻ ያለመሳተፍ',                  'name_en' => 'Institution not joining cleaning campaign',           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-022', 'name_am' => 'ቤተሰብ ማፅዳት ዘመቻ ያለመሳተፍ',                 'name_en' => 'Household not joining cleaning campaign',             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 300.00],
                    ['code' => 'S2-023', 'name_am' => 'የንፅህና ድርጅት መሳሪያ ማጣት',                  'name_en' => 'Sanitary organization missing safety materials',       'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-024', 'name_am' => 'ያልተፈቀደ ወደ ቆሻሻ መጣያ ማጓጓዝ',              'name_en' => 'Unauthorized transport to disposal site',             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 10000.00],
                    ['code' => 'S2-025', 'name_am' => 'ከከተማ ውጭ ቆሻሻ ማጓጓዝ',                    'name_en' => 'Waste transport from outside the city',               'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 20000.00],
                    ['code' => 'S2-026', 'name_am' => 'የግል ንፅህና ድርጅት ያለ ፈቃድ ቦታ ውጭ',         'name_en' => 'Private sanitary org operating outside allocation',   'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-027', 'name_am' => 'ያልተፈቀደ የግንባታ ቆሻሻ',                     'name_en' => 'Unauthorized construction waste disposal',            'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 5000.00],
                    ['code' => 'S2-028', 'name_am' => 'ጎዳና ላይ ጭቃ',                             'name_en' => 'Mud/dirt on road',                                    'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 2000.00],
                    ['code' => 'S2-029', 'name_am' => 'ያልተፈቀደ አጥንት/ስጋ ቆሻሻ',                   'name_en' => 'Unauthorized bone/meat byproducts disposal',          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-030', 'name_am' => 'ያለፈቃድ አደገኛ ቆሻሻ',                       'name_en' => 'Dangerous waste without permit',                      'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 10000.00],
                    ['code' => 'S2-031', 'name_am' => 'ያልተለጠፈ ኤሌክትሮኒክስ ቆሻሻ',                  'name_en' => 'Electronic waste not identified/labeled',             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 1000.00],
                    ['code' => 'S2-032', 'name_am' => 'ቤት ፊት 5ሜ. አለማፅዳት (ቤተሰብ)',              'name_en' => 'Not cleaning 5m outside house (household)',           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 300.00],
                    ['code' => 'S2-033', 'name_am' => 'ቤት ፊት 5ሜ. አለማፅዳት (ድርጅት)',              'name_en' => 'Not cleaning 5m outside premises (organization)',     'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 2', 'fine_amount' => 5000.00],
                ],
            ],

            // ---- SCHEDULE 3 — ትራፊክ እና የመንገድ ደህንነት ----
            [
                'name_am'     => 'ሰንጠረዥ 3 — ትራፊክ እና የመንገድ ደህንነት',
                'name_en'     => 'Schedule 3 — Traffic and Road Safety',
                'level'       => 3,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S3-001', 'name_am' => 'አሸዋ/ድንጋይ መንገድ ላይ',                      'name_en' => 'Sand/stones on road',                                 'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 2000.00],
                    ['code' => 'S3-002', 'name_am' => 'ብረት ቆራጭ ሰራተኛ',                          'name_en' => 'Metal cutting on street (worker)',                    'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 50.00],
                    ['code' => 'S3-003', 'name_am' => 'ብረት ቆራጭ ቀጣሪ',                           'name_en' => 'Metal cutting on street (employer)',                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 500.00],
                    ['code' => 'S3-004', 'name_am' => 'ጭቃ (ሰራተኛ)',                              'name_en' => 'Mud on road (worker)',                                'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 50.00],
                    ['code' => 'S3-005', 'name_am' => 'ጭቃ (ቀጣሪ)',                               'name_en' => 'Mud on road (employer)',                              'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 500.00],
                    ['code' => 'S3-006', 'name_am' => 'ለምለም ወደ የተጠበቀ የመንገድ ቦታ ማሳደግ',          'name_en' => 'Planting on road-reserved land',                      'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 3000.00],
                    ['code' => 'S3-007', 'name_am' => 'ድልድይ/የመንገድ መብራት ጉዳት',                   'name_en' => 'Damaging bridges or road lights',                     'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 500.00],
                    ['code' => 'S3-008', 'name_am' => 'ህገ-ወጥ መንገድ ቀለጠፍ/መዝጋት',                 'name_en' => 'Illegal road modification/blocking',                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 3000.00],
                    ['code' => 'S3-009', 'name_am' => 'ድንኳን ትርኪምርኪ',                           'name_en' => 'Tent blocking road',                                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 200.00],
                    ['code' => 'S3-010', 'name_am' => 'ህገ-ወጥ የጎዳና ዳር ንግድ',                     'name_en' => 'Illegal street vending',                              'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 500.00],
                    ['code' => 'S3-011', 'name_am' => 'የካሻ ቆሻሻ ጎዳና ላይ',                        'name_en' => 'Construction waste on road',                          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 1000.00],
                    ['code' => 'S3-012', 'name_am' => 'ሽንት ቤት ፍሳሽ ጎዳና ላይ',                     'name_en' => 'Toilet sewage on road',                               'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 1000.00],
                    ['code' => 'S3-013', 'name_am' => 'ጎዳና ላይ የመኪና ጥገና (ሰራተኛ)',                'name_en' => 'Car repair on street (worker)',                        'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 500.00],
                    ['code' => 'S3-014', 'name_am' => 'ጎዳና ላይ የመኪና ጥገና (ቀጣሪ)',                 'name_en' => 'Car repair on street (employer)',                      'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 1000.00],
                    ['code' => 'S3-015', 'name_am' => 'ጎዳና ላይ የመኪና ማጠቢያ (ሰራተኛ)',               'name_en' => 'Car wash on street (worker)',                          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 50.00],
                    ['code' => 'S3-016', 'name_am' => 'ጎዳና ላይ የመኪና ማጠቢያ (ቀጣሪ)',                'name_en' => 'Car wash on street (employer)',                        'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 200.00],
                    ['code' => 'S3-017', 'name_am' => 'ጋራጅ ውጭ ሜዳ (ድርጅት)',                     'name_en' => 'Garage outside premises (organization)',               'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 3000.00],
                    ['code' => 'S3-018', 'name_am' => 'ጋራጅ ውጭ ሜዳ (ቀጣሪ)',                      'name_en' => 'Garage outside premises (employer)',                   'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 200.00],
                    ['code' => 'S3-019', 'name_am' => 'ህዝባዊ ሜዳ ንግድ',                           'name_en' => 'Business on pedestrian roads',                        'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 5000.00],
                    ['code' => 'S3-020', 'name_am' => 'ጎዳና ዛፍ/እፅዋ ቁረጣ',                       'name_en' => 'Cutting roadside plants',                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 3', 'fine_amount' => 100.00],
                ],
            ],

            // ---- SCHEDULE 4 — ህገ-ወጥ ውጭ ማስታወቂያ ----
            [
                'name_am'     => 'ሰንጠረዥ 4 — ህገ-ወጥ ውጭ ማስታወቂያ',
                'name_en'     => 'Schedule 4 — Illegal Outdoor Advertisements',
                'level'       => 4,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S4-001', 'name_am' => 'ትራፊክ ብርሃን/ምሰሶ ላይ ማስታወቂያ',             'name_en' => 'Advertisement on traffic lights/poles',               'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 4', 'fine_amount' => 1000.00],
                    ['code' => 'S4-002', 'name_am' => 'ድልድይ/ጎዳና ላይ ማስታወቂያ',                   'name_en' => 'Advertisement on roads/bridges',                      'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 4', 'fine_amount' => 1000.00],
                    ['code' => 'S4-003', 'name_am' => 'ሽፋሽፍ አዳሪ',                              'name_en' => 'Distributing fliers (distributor)',                    'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 4', 'fine_amount' => 50.00],
                    ['code' => 'S4-004', 'name_am' => 'ሽፋሽፍ ድርጅት/ባለቤት',                       'name_en' => 'Distributing fliers (company/owner)',                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 4', 'fine_amount' => 500.00],
                ],
            ],

            // ---- SCHEDULE 5 — ቅሌታም ድርጊቶች ----
            [
                'name_am'     => 'ሰንጠረዥ 5 — ቅሌታም ድርጊቶች',
                'name_en'     => 'Schedule 5 — Disturbing Activities',
                'level'       => 5,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S5-001', 'name_am' => 'ጫት ቃሚ ለኑሮ',                             'name_en' => 'Chat chewing for income',                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 5000.00],
                    ['code' => 'S5-002', 'name_am' => 'ጫት ቃሚ ሕዝባዊ ቦታ (ለሰው)',                   'name_en' => 'Chat chewing in public space (per person)',            'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 100.00],
                    ['code' => 'S5-003', 'name_am' => 'ሺሻ ሽያጭ',                                 'name_en' => 'Shisha smoking for income',                           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 10000.00],
                    ['code' => 'S5-004', 'name_am' => 'ፊልም/ቪዲዮ ከትምህርት ቤት 200ሜ.',              'name_en' => 'Film/video within 200m of school',                    'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 2000.00],
                    ['code' => 'S5-005', 'name_am' => 'ቀን/ሌሊት ዳንስ ከትምህርት ቤት 200ሜ.',          'name_en' => 'Day/night dancing within 200m of school',             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 10000.00],
                    ['code' => 'S5-006', 'name_am' => 'አልኮሆል ሽያጭ (ድርጅት)',                      'name_en' => 'Selling alcohol (organization)',                       'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 2000.00],
                    ['code' => 'S5-007', 'name_am' => 'አልኮሆል ሽያጭ (ግለሰብ)',                       'name_en' => 'Selling alcohol (individual)',                         'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 1000.00],
                    ['code' => 'S5-008', 'name_am' => 'ሽርጥ',                                    'name_en' => 'Gambling',                                            'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 15000.00],
                    ['code' => 'S5-009', 'name_am' => 'ስትሪፕቴዝ',                                 'name_en' => 'Striptease',                                          'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 10000.00],
                    ['code' => 'S5-010', 'name_am' => 'ጡረታ ቤት',                                 'name_en' => 'Pension house',                                       'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 10000.00],
                    ['code' => 'S5-011', 'name_am' => 'ማሳጅ',                                    'name_en' => 'Massage',                                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 15000.00],
                    ['code' => 'S5-012', 'name_am' => 'ቅሌታም ቦታ ለኪራይ',                         'name_en' => 'Leasing property for disturbing activities',           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 5', 'fine_amount' => 3000.00],
                ],
            ],

            // ---- SCHEDULE 6 — ህገ-ወጥ እንስሳ ዝውውር/ሽያጭ/ምርስ ----
            [
                'name_am'     => 'ሰንጠረዥ 6 — ህገ-ወጥ እንስሳ ዝውውር/ሽያጭ/ምርስ',
                'name_en'     => 'Schedule 6 — Illegal Animal Circulation, Trading and Slaughtering',
                'level'       => 6,
                'description' => 'ደንብ 150/2023',
                'violations'  => [
                    ['code' => 'S6-001', 'name_am' => 'ህገ-ወጥ ቅር (አቅራቢ)',                       'name_en' => 'Illegal slaughtering — provider',                     'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 15000.00],
                    ['code' => 'S6-002', 'name_am' => 'ህገ-ወጥ ቅር (ግለሰብ ሸማቺ)',                  'name_en' => 'Illegal slaughtering — individual consumer',           'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 1500.00],
                    ['code' => 'S6-003', 'name_am' => 'ህገ-ወጥ ቅር (ዝቅተኛ ሸማቺ)',                  'name_en' => 'Illegal slaughtering — business consumer',             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 10000.00],
                    ['code' => 'S6-004', 'name_am' => 'ሰዓሽ (ለነፍስ)',                             'name_en' => 'Slaughterer (per animal)',                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 300.00],
                    ['code' => 'S6-005', 'name_am' => 'ፍልሰ/ዳፊቃ ጎዳና ላይ (ለነፍስ)',               'name_en' => 'Animal grazing on road (per animal)',                  'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 60.00],
                    ['code' => 'S6-006', 'name_am' => 'ህገ-ወጥ ስጋ ዝውውር',                        'name_en' => 'Illegal meat trafficking',                             'regulation_reference' => 'ደንብ 150/2023 ሰንጠረዥ 6', 'fine_amount' => 1500.00],
                ],
            ],

            // ---- SCHEDULE 7 — የወንዝ ዳርቻ ልማት እና ብክለት መከላከል (Reg 180/2024) ----
            // Riverbank = minimum 30m each side. Repeat offense: double fine +
            // business license revoked and/or water supply cutoff.
            [
                'name_am'     => 'ሰንጠረዥ 7 — የወንዝ ዳርቻ ልማት እና ብክለት መከላከል',
                'name_en'     => 'Schedule 7 — Riverbank Development and Pollution Prevention',
                'level'       => 7,
                'description' => 'ደንብ 180/2024',
                'violations'  => [
                    ['code' => 'RB-001',  'name_am' => 'ከተቋም ኬሚካዊ ቆሻሻ',                        'name_en' => 'Chemical waste from institutions',                    'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-002a', 'name_am' => 'ጠጣር ቆሻሻ ወንዝ ዳርቻ (ግለሰብ)',               'name_en' => 'Solid waste in riverbank (individual)',               'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 20000.00],
                    ['code' => 'RB-002b', 'name_am' => 'ጠጣር ቆሻሻ ወንዝ ዳርቻ (ድርጅት)',               'name_en' => 'Solid waste in riverbank (organization)',             'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-003a', 'name_am' => 'ቤተሰብ ሽንት ቤት ፍሳሽ (ግለሰብ)',               'name_en' => 'Residential toilet sewage in riverbank (individual)',  'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 150000.00],
                    ['code' => 'RB-003b', 'name_am' => 'ቤተሰብ ሽንት ቤት ፍሳሽ (ድርጅት)',               'name_en' => 'Residential toilet sewage in riverbank (organization)', 'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 300000.00],
                    ['code' => 'RB-004a', 'name_am' => 'ፈቃድ ሳይጠየቅ የቆሻሻ ፍሳሽ (ግለሰብ)',            'name_en' => 'Treated sewage without consent (individual)',         'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 15000.00],
                    ['code' => 'RB-004b', 'name_am' => 'ፈቃድ ሳይጠየቅ የቆሻሻ ፍሳሽ (ድርጅት)',            'name_en' => 'Treated sewage without consent (organization)',       'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 40000.00],
                    ['code' => 'RB-005a', 'name_am' => 'የቀነሰ ጥራት ፍሳሽ (ግለሰብ)',                  'name_en' => 'Reduced quality treatment effluent (individual)',     'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 50000.00],
                    ['code' => 'RB-005b', 'name_am' => 'የቀነሰ ጥራት ፍሳሽ (ድርጅት)',                  'name_en' => 'Reduced quality treatment effluent (organization)',   'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-005c', 'name_am' => 'ኬሚካሉ ያመረተ ፍሳሽ',                        'name_en' => 'Toxic chemical-generating effluent',                  'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-005d', 'name_am' => 'pH <6.0 ወይም >9.0',                      'name_en' => 'Effluent pH below 6.0 or above 9.0',                 'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-005e', 'name_am' => 'ትናፋፊ ንጥረ-ነገር (<100°C)',                  'name_en' => 'Substances that evaporate below 100°C',               'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-005f', 'name_am' => 'ፈንጂ/አደገኛ ጋዝ ያለው ፍሳሽ',                  'name_en' => 'Explosive/dangerous gas effluent',                   'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-005g', 'name_am' => 'ዘይት/ነዳጅ/ሳሙና ፍሳሽ',                      'name_en' => 'Oil/fuel/soapy substance drainage',                   'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-005h', 'name_am' => 'ዕፅዋት/ቅርጫ ቆሻሻ (ግለሰብ)',                  'name_en' => 'Vegetable/wood/gravel debris in riverbank (individual)', 'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 20000.00],
                    ['code' => 'RB-005i', 'name_am' => 'ዕፅዋት/ቅርጫ ቆሻሻ (ድርጅት)',                  'name_en' => 'Vegetable/wood/gravel debris in riverbank (organization)', 'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-006a', 'name_am' => 'ፍሳሽ ወደ ጎርፍ ቦይ (ግለሰብ)',                 'name_en' => 'Sewer connected to flood drain (individual)',          'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 50000.00],
                    ['code' => 'RB-006b', 'name_am' => 'ፍሳሽ ወደ ጎርፍ ቦይ (ድርጅት)',                 'name_en' => 'Sewer connected to flood drain (organization)',        'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-007a', 'name_am' => 'ኬሚካሉ ያለ ፈቃድ (ግለሰብ)',                   'name_en' => 'Chemical spills without treatment (individual)',       'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 50000.00],
                    ['code' => 'RB-007b', 'name_am' => 'ኬሚካሉ ያለ ፈቃድ (ድርጅት)',                   'name_en' => 'Chemical spills without treatment (organization)',     'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 300000.00],
                    ['code' => 'RB-008',  'name_am' => 'ያልታጠቡ ፍሳሽ',                            'name_en' => 'Untreated industrial/service sewage',                 'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 300000.00],
                    ['code' => 'RB-009',  'name_am' => 'ሽንት ቤት ፍሳሽ ወደ ወንዝ',                    'name_en' => 'Toilet waste directly in river',                      'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 100000.00],
                    ['code' => 'RB-010a', 'name_am' => 'አደገኛ ቆሻሻ ወንዝ ዳርቻ (ግለሰብ)',              'name_en' => 'Hazardous waste in riverbank (individual)',            'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 500000.00],
                    ['code' => 'RB-010b', 'name_am' => 'አደገኛ ቆሻሻ ወንዝ ዳርቻ (ድርጅት)',              'name_en' => 'Hazardous waste in riverbank (organization)',          'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 1000000.00],
                    ['code' => 'RB-011',  'name_am' => 'ወንዝ ዳርቻ ሰአሸ',                           'name_en' => 'Defecating in riverbank zone',                        'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 2000.00],
                    ['code' => 'RB-012',  'name_am' => 'ያለ ፈቃድ የመኪና ማጠቢያ ወንዝ ዳርቻ',            'name_en' => 'Car washing without treatment in riverbank',          'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 400000.00],
                    ['code' => 'RB-013',  'name_am' => 'ወንዝ ዳርቻ እንስሳ ቅርስ',                     'name_en' => 'Animal grazing in riverbank zone',                    'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 30000.00],
                    ['code' => 'RB-014',  'name_am' => 'ያልተፈቀደ ወንዝ ዳርቻ ግንባታ',                 'name_en' => 'Unauthorized construction in riverbank zone',         'regulation_reference' => 'ደንብ 180/2024 ሰንጠረዥ 1', 'fine_amount' => 200000.00],
                ],
            ],
        ];

        foreach ($schedules as $sData) {
            $violations = $sData['violations'];
            unset($sData['violations']);
            $schedule = PenaltySchedule::updateOrCreate(['level' => $sData['level']], $sData + ['is_active' => true]);
            foreach ($violations as $vd) {
                $vt = ViolationType::firstOrCreate(['code' => $vd['code']], $vd + ['penalty_schedule_id' => $schedule->id, 'is_active' => true]);
                $vtIds[$vd['code']] = $vt->id;
            }
        }

        // ============================================================
        // SCENARIO DATA — locations + demo users (created if absent)
        // ============================================================
        $addisKetema = SubCity::where('code', 1)->first();
        $akakiKality = SubCity::where('code', 2)->first();
        $arada       = SubCity::where('code', 3)->first();
        $bole        = SubCity::where('code', 4)->first();

        if (! $addisKetema) {
            $this->command?->warn('PenaltyModuleSeeder: schedules seeded. Skipping scenarios — AddisAbabaLocationSeeder has not run yet.');
            return;
        }

        $akW1 = Woreda::where('sub_city_id', $addisKetema->id)->orderBy('id')->first();
        $akW2 = Woreda::where('sub_city_id', $addisKetema->id)->orderBy('id')->skip(1)->first();
        $aqW1 = Woreda::where('sub_city_id', $akakiKality->id)->orderBy('id')->first();
        $arW1 = Woreda::where('sub_city_id', $arada->id)->orderBy('id')->first();
        $arW2 = Woreda::where('sub_city_id', $arada->id)->orderBy('id')->skip(1)->first();
        $blW1 = Woreda::where('sub_city_id', $bole->id)->orderBy('id')->first();

        $mkUser = function (string $email, string $name, string $username, string $role) {
            $u = User::firstOrCreate(['email' => $email], [
                'name' => $name, 'username' => $username,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
            $u->syncRoles([$role]);
            return $u;
        };

        $admin           = User::where('email', 'admin@aalea.gov.et')->first()
                           ?? $mkUser('admin@aalea.gov.et', 'Super Admin', 'pm_super_admin', 'admin');
        $officerAKW1     = $mkUser('officer.akw1@aalea.gov.et',     'Officer AK-W1',        'pm_officer_akw1',   'officer');
        $officerAKW2     = $mkUser('officer.akw2@aalea.gov.et',     'Officer Boka',         'pm_officer_boka',   'officer');
        $supervisorAK    = $mkUser('supervisor.ak@aalea.gov.et',    'Abera Supervisor',     'pm_sup_ak',         'supervisor');
        $officerAradaW1  = $mkUser('officer.arada1@aalea.gov.et',   'Arada W1 Officer',     'pm_officer_ar1',    'officer');
        $officerAkaki    = $mkUser('officer.akaki@aalea.gov.et',    'Akaki Officer',        'pm_officer_aq',     'officer');
        $supervisorAkaki = $mkUser('supervisor.akaki@aalea.gov.et', 'Akaki Supervisor',     'pm_sup_aq',         'supervisor');
        $supervisorArada = $mkUser('supervisor.arada@aalea.gov.et', 'Arada Supervisor',     'pm_sup_ar',         'supervisor');
        $officerAradaW2  = $mkUser('officer.arada2@aalea.gov.et',   'Arada W2 Officer',     'pm_officer_ar2',    'officer');
        $officerBole     = $mkUser('officer.bole@aalea.gov.et',     'Olyad Akaki',          'pm_officer_bole',   'officer');

        $officerAKW1->update(['sub_city' => $addisKetema->id, 'woreda' => $akW1->id]);
        $officerAKW2->update(['sub_city' => $addisKetema->id, 'woreda' => $akW2->id]);
        $supervisorAK->update(['sub_city' => $addisKetema->id, 'woreda' => $akW1->id]);
        $officerAradaW1->update(['sub_city' => $arada->id, 'woreda' => $arW1->id]);
        $officerAradaW2->update(['sub_city' => $arada->id, 'woreda' => $arW2->id]);
        $supervisorArada->update(['sub_city' => $arada->id, 'woreda' => null]);
        $officerAkaki->update(['sub_city' => $akakiKality->id, 'woreda' => $aqW1->id]);
        $supervisorAkaki->update(['sub_city' => $akakiKality->id, 'woreda' => $aqW1->id]);
        $officerBole->update(['sub_city' => $bole->id, 'woreda' => $blW1->id]);

        // ============================================================
        // SCENARIO 2: DIRECT FINE → PAID ON TIME
        // Street vendor caught → receipt issued → paid within 3 working days
        // Art. 15: pay within 3 consecutive working days at woreda office or electronically
        // ============================================================
        $this->command->info('--- Scenario 2: Direct Fine → Paid On Time ---');

        $v_scenario2 = Violator::create([
            'type' => 'individual', 'full_name_am' => 'ተስፋዬ በቀለ', 'full_name_en' => 'Tesfaye Bekele',
            'phone' => '0911111111', 'id_number' => 'SC2-001',
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ገበያ ዋና መንገድ',
        ]);

        $rec_sc2 = ViolationRecord::create([
            'violator_id' => $v_scenario2->id, 'violation_type_id' => $vtIds['S3-010'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'block' => 'A-3', 'specific_location' => 'ፒያሳ ገበያ ዋና መንገድ',
            'violation_date' => Carbon::now()->subDays(10), 'violation_time' => '08:30:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 3',
            'fine_amount' => 500.00, 'status' => 'paid',
            'action_taken' => 'ቅጣት ደረሰኝ ተሰጥቷል። በ3 ቀን ውስጥ ክፍያ ተፈጽሟል።',
            'reported_by' => $officerAKW1->id, 'verified_by' => $supervisorAK->id,
        ]);

        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc2->id, 'receipt_number' => 'PR-2026-SC2-001',
            'issued_date' => Carbon::now()->subDays(10), 'issued_time' => '09:00:00',
            'fine_amount' => 500.00, 'payment_deadline' => Carbon::now()->subDays(7),
            'paid_date' => Carbon::now()->subDays(8), 'paid_amount' => 500.00,
            'payment_status' => 'paid',
            'issued_by' => $officerAKW1->id,
        ]);

        // ============================================================
        // SCENARIO 3: RECEIPT REFUSED → 3 WITNESSES REQUIRED
        // Violator refuses receipt → 3 officers sign → posted at location
        // Art. 15: refuse notice = deemed received
        // ============================================================
        $this->command->info('--- Scenario 3: Receipt Refused + 3 Witnesses ---');

        $v_scenario3 = Violator::create([
            'type' => 'individual', 'full_name_am' => 'ከበደ ታደሰ', 'full_name_en' => 'Kebede Tadesse',
            'phone' => '0922222222', 'id_number' => 'SC3-001',
            'sub_city_id' => $arada->id, 'woreda_id' => $arW1->id,
            'specific_location' => 'አራዳ ጊዮርጊስ አካባቢ',
        ]);

        $rec_sc3 = ViolationRecord::create([
            'violator_id' => $v_scenario3->id, 'violation_type_id' => $vtIds['S4-001'],
            'sub_city_id' => $arada->id, 'woreda_id' => $arW1->id,
            'specific_location' => 'አራዳ ጊዮርጊስ ዋና መንገድ',
            'violation_date' => Carbon::now()->subDays(5), 'violation_time' => '10:15:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 4',
            'fine_amount' => 1000.00, 'status' => 'payment_pending',
            'action_taken' => 'ደንብ ተላላፊው ደረሰኙን አልቀበልም ብሏል። 3 ምስክሮች ፈርመዋል።',
            'reported_by' => $officerAradaW1->id, 'verified_by' => $supervisorArada->id,
        ]);

        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc3->id, 'receipt_number' => 'PR-2026-SC3-001',
            'issued_date' => Carbon::now()->subDays(5), 'issued_time' => '10:30:00',
            'fine_amount' => 1000.00, 'payment_deadline' => Carbon::now()->subDays(2),
            'payment_status' => 'pending',
            'receipt_refused' => true,
            'issued_by' => $officerAradaW1->id,
            'witness_officer_1' => $officerAradaW2->id,
            'witness_officer_2' => $supervisorArada->id,
            'witness_officer_3' => $admin->id,
            'notes' => 'ደንብ ተላላፊው ደረሰኙን አልቀበልም ብሏል። ማስታወቂያው በቦታው ተለጥፏል።',
        ]);

        // ============================================================
        // SCENARIO 4: NON-PAYMENT → COURT ESCALATION (FINE DOUBLED)
        // Art. 15: non-payment → double fine; Art. 16: refuses double → court
        // ============================================================
        $this->command->info('--- Scenario 4: Non-Payment → Court (Fine Doubled) ---');

        $v_scenario4 = Violator::create([
            'type' => 'individual', 'full_name_am' => 'አለሙ ገብረ', 'full_name_en' => 'Alemu Gebre',
            'phone' => '0933333333', 'id_number' => 'SC4-001',
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW2->id,
            'specific_location' => 'መርካቶ ገበያ',
        ]);

        $rec_sc4 = ViolationRecord::create([
            'violator_id' => $v_scenario4->id, 'violation_type_id' => $vtIds['S3-010'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW2->id,
            'block' => 'B-7', 'specific_location' => 'መርካቶ ገበያ ውስጥ',
            'violation_date' => Carbon::now()->subDays(20), 'violation_time' => '14:00:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 3',
            'fine_amount' => 500.00, 'status' => 'court_filed',
            'action_taken' => 'በ3 ቀን ውስጥ ክፍያ አልፈጸመም። ቅጣት እጥፍ ሆኗል (1,000 ብር)። ክስ ቀርቧል።',
            'reported_by' => $officerAKW2->id, 'verified_by' => $supervisorAK->id,
        ]);

        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc4->id, 'receipt_number' => 'PR-2026-SC4-001',
            'issued_date' => Carbon::now()->subDays(20), 'issued_time' => '14:30:00',
            'fine_amount' => 500.00, 'payment_deadline' => Carbon::now()->subDays(17),
            'payment_status' => 'court_filed',
            'is_court_case' => true,
            'court_filed_date' => Carbon::now()->subDays(15),
            'court_fine_amount' => 1000.00,
            'issued_by' => $officerAKW2->id,
            'notes' => 'በ3 ቀን ክፍያ አልተፈጸመም። ቅጣት እጥፍ ሆነ (ብር 1,000)። ክስ ቀረበ።',
        ]);

        // ============================================================
        // SCENARIO 5a: 3-DAY WARNING → COMPLIED
        // Unauthorized construction → 3-day warning → violator demolished
        // ============================================================
        $this->command->info('--- Scenario 5a: 3-Day Warning → Complied ---');

        $v_scenario5a = Violator::create([
            'type' => 'individual', 'full_name_am' => 'ሰለሞን ዘውዴ', 'full_name_en' => 'Solomon Zewde',
            'phone' => '0944444444', 'id_number' => 'SC5A-001',
            'sub_city_id' => $arada->id, 'woreda_id' => $arW2->id,
            'specific_location' => 'ሰዳስት ኪሎ', 'house_number' => 'H-22',
        ]);

        $rec_sc5a = ViolationRecord::create([
            'violator_id' => $v_scenario5a->id, 'violation_type_id' => $vtIds['S1-004'],
            'sub_city_id' => $arada->id, 'woreda_id' => $arW2->id,
            'specific_location' => 'ሰዳስት ኪሎ ቤት ቁ. H-22',
            'violation_date' => Carbon::now()->subDays(12), 'violation_time' => '11:00:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'fine_amount' => 3000.00, 'status' => 'closed',
            'action_taken' => 'የ3 ቀን ማስጠንቀቂያ ተሰጠ። ደንብ ተላላፊው በጊዜው አፍርሷል። ጉዳይ ተዘግቷል።',
            'reported_by' => $officerAradaW2->id, 'verified_by' => $supervisorArada->id,
        ]);

        WarningLetter::create([
            'violation_record_id' => $rec_sc5a->id, 'reference_number' => 'WL-2026-SC5A-001',
            'warning_type' => 'three_day',
            'issued_date' => Carbon::now()->subDays(12), 'deadline' => Carbon::now()->subDays(9),
            'complied' => true, 'complied_at' => Carbon::now()->subDays(10),
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'delivery_method' => 'in_person', 'violator_accepted' => true,
            'issued_by' => $officerAradaW2->id, 'issued_by_officer_2' => $supervisorArada->id,
        ]);

        // ============================================================
        // SCENARIO 5b: 3-DAY WARNING → NOT COMPLIED → PENALTY ISSUED
        // ============================================================
        $this->command->info('--- Scenario 5b: 3-Day Warning → Not Complied → Penalty ---');

        $v_scenario5b = Violator::create([
            'type' => 'organization', 'full_name_am' => 'ፍቅር ንግድ ድርጅት', 'full_name_en' => 'Fikir Trading PLC',
            'phone' => '0115555555', 'id_number' => 'SC5B-BIZ-001',
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ንግድ ማዕከል', 'house_number' => 'Shop-14',
        ]);

        $rec_sc5b = ViolationRecord::create([
            'violator_id' => $v_scenario5b->id, 'violation_type_id' => $vtIds['S4-004'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'block' => 'A-1', 'specific_location' => 'ፒያሳ ንግድ ማዕከል Shop-14',
            'violation_date' => Carbon::now()->subDays(8), 'violation_time' => '09:00:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 4',
            'fine_amount' => 500.00, 'status' => 'penalty_issued',
            'action_taken' => 'የ3 ቀን ማስጠንቀቂያ ተሰጠ። ባለመፈጸሙ ቅጣት ደረሰኝ ተሰጥቷል።',
            'reported_by' => $officerAKW1->id, 'verified_by' => $supervisorAK->id,
        ]);

        WarningLetter::create([
            'violation_record_id' => $rec_sc5b->id, 'reference_number' => 'WL-2026-SC5B-001',
            'warning_type' => 'three_day',
            'issued_date' => Carbon::now()->subDays(8), 'deadline' => Carbon::now()->subDays(5),
            'complied' => false,
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 4',
            'delivery_method' => 'in_person', 'violator_accepted' => true,
            'issued_by' => $officerAKW1->id,
        ]);

        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc5b->id, 'receipt_number' => 'PR-2026-SC5B-001',
            'issued_date' => Carbon::now()->subDays(4), 'issued_time' => '10:00:00',
            'fine_amount' => 500.00, 'payment_deadline' => Carbon::now()->subDays(1),
            'payment_status' => 'pending',
            'issued_by' => $officerAKW1->id,
        ]);

        // ============================================================
        // SCENARIO 6: 24-HOUR WARNING → TASK FORCE DEMOLITION
        // Land expansion (S1-002): no cash fine — administrative action is stop + demolish
        // ============================================================
        $this->command->info('--- Scenario 6: 24-Hour Warning → Task Force Demolition ---');

        $v_scenario6 = Violator::create([
            'type' => 'individual', 'full_name_am' => 'ሙሉጌታ አሰፋ', 'full_name_en' => 'Mulugeta Assefa',
            'phone' => '0966666666', 'id_number' => 'SC6-001',
            'sub_city_id' => $bole->id, 'woreda_id' => $blW1->id,
            'specific_location' => 'ቦሌ ሜዳ አካባቢ', 'house_number' => 'N/A',
        ]);

        $rec_sc6 = ViolationRecord::create([
            'violator_id' => $v_scenario6->id, 'violation_type_id' => $vtIds['S1-002'],
            'sub_city_id' => $bole->id, 'woreda_id' => $blW1->id,
            'block' => 'D-2', 'specific_location' => 'ቦሌ ሜዳ ህገ-ወጥ ግንባታ',
            'violation_date' => Carbon::now()->subDays(30), 'violation_time' => '07:00:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'fine_amount' => 0.00, 'status' => 'closed',
            'action_taken' => 'የ24 ሰዓት ማስጠንቀቂያ ተሰጠ። ባለመፈጸሙ በግብረ ኃይል ፈርሷል።',
            'investigation_notes' => 'ህገ-ወጥ መሬት ማስፋፋት። ከ24 ሰዓት ማስጠንቀቂያ በኋላ ግብረ ኃይል ተሰማርቷል።',
            'reported_by' => $officerBole->id,
        ]);

        WarningLetter::create([
            'violation_record_id' => $rec_sc6->id, 'reference_number' => 'WL-2026-SC6-001',
            'warning_type' => 'twenty_four_hour',
            'issued_date' => Carbon::now()->subDays(30), 'deadline' => Carbon::now()->subDays(29),
            'complied' => false,
            'escalated_to_task_force' => true, 'escalation_date' => Carbon::now()->subDays(29),
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'delivery_method' => 'in_person', 'violator_accepted' => true,
            'issued_by' => $officerBole->id,
            'notes' => 'ንብረትዎን በ24 ሰዓት ውስጥ ያውጡ። በግብረ ኃይል ይፈርሳል።',
        ]);

        // ============================================================
        // SCENARIO 7: BEYOND OFFICER CAPACITY → TASK FORCE
        // ============================================================
        $this->command->info('--- Scenario 7: Task Force Escalation (Beyond Officer Capacity) ---');

        $v_scenario7 = Violator::create([
            'type' => 'organization', 'full_name_am' => 'ኢትዮ ኮንስትራክሽን', 'full_name_en' => 'Ethio Construction PLC',
            'phone' => '0115777777', 'id_number' => 'SC7-BIZ-001',
            'sub_city_id' => $akakiKality->id, 'woreda_id' => $aqW1->id,
            'specific_location' => 'ካልቲ ኢንዱስትሪ ዞን',
        ]);

        $rec_sc7 = ViolationRecord::create([
            'violator_id' => $v_scenario7->id, 'violation_type_id' => $vtIds['S1-002'],
            'sub_city_id' => $akakiKality->id, 'woreda_id' => $aqW1->id,
            'block' => 'IND-5', 'specific_location' => 'ካልቲ ኢንዱስትሪ ዞን ብሎክ 5',
            'violation_date' => Carbon::now()->subDays(3), 'violation_time' => '06:00:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'fine_amount' => 0.00, 'status' => 'open',
            'action_taken' => 'ከኦፊሰሩ አቅም በላይ። ለሽፍት መሪ ተሳውቋል። ግብረ ኃይል ያስፈልጋል።',
            'investigation_notes' => 'ትልቅ ድርጅት በሰፊ ቦታ ላይ ህገ-ወጥ መሬት ማስፋፋት። ግብረ ኃይል አፍራሽ ቡድን ያስፈልጋል።',
            'reported_by' => $officerAkaki->id, 'verified_by' => $supervisorAkaki->id,
        ]);

        // ============================================================
        // SCENARIO 8: REPEAT OFFENDER (3rd TIME)
        // Art. 16: 1st offense standard; 2nd same offense double; still fails → court
        // ============================================================
        $this->command->info('--- Scenario 8: Repeat Offender (3 Violations) ---');

        $v_scenario8 = Violator::create([
            'type' => 'individual', 'full_name_am' => 'ዳዊት መኮንን', 'full_name_en' => 'Dawit Mekonnen',
            'phone' => '0988888888', 'id_number' => 'SC8-001',
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ገበያ',
        ]);

        // 1st offense — standard fine (Birr 500)
        $rec_sc8_1 = ViolationRecord::create([
            'violator_id' => $v_scenario8->id, 'violation_type_id' => $vtIds['S2-001'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ገበያ ደቡብ በር',
            'violation_date' => Carbon::now()->subDays(45), 'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 2',
            'fine_amount' => 500.00, 'repeat_offense_count' => 0, 'status' => 'paid',
            'action_taken' => 'የመጀመሪያ ጥፋት። ቅጣት ደረሰኝ ተሰጥቷል። ክፍያ ተፈጽሟል።',
            'reported_by' => $officerAKW1->id,
        ]);
        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc8_1->id, 'receipt_number' => 'PR-2026-SC8-001',
            'issued_date' => Carbon::now()->subDays(45), 'fine_amount' => 500.00,
            'payment_deadline' => Carbon::now()->subDays(42), 'paid_date' => Carbon::now()->subDays(43),
            'paid_amount' => 500.00, 'payment_status' => 'paid',
            'issued_by' => $officerAKW1->id,
        ]);

        // 2nd offense — same violation → doubled fine (Birr 1,000) per Art. 16
        $rec_sc8_2 = ViolationRecord::create([
            'violator_id' => $v_scenario8->id, 'violation_type_id' => $vtIds['S2-001'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ገበያ ደቡብ በር',
            'violation_date' => Carbon::now()->subDays(20), 'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 2',
            'fine_amount' => 1000.00, 'repeat_offense_count' => 1, 'status' => 'paid',
            'action_taken' => '2ኛ ጥፋት (ተመሳሳይ)። ቅጣት እጥፍ ሆኗል (ብር 1,000)። ማስጠንቀቂያ ከቅጣት ጋር ተሰጥቷል።',
            'reported_by' => $officerAKW1->id, 'verified_by' => $supervisorAK->id,
        ]);
        WarningLetter::create([
            'violation_record_id' => $rec_sc8_2->id, 'reference_number' => 'WL-2026-SC8-001',
            'warning_type' => 'three_day',
            'issued_date' => Carbon::now()->subDays(20), 'deadline' => Carbon::now()->subDays(17),
            'complied' => true, 'complied_at' => Carbon::now()->subDays(18),
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 2',
            'delivery_method' => 'in_person', 'violator_accepted' => true,
            'issued_by' => $officerAKW1->id,
        ]);
        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc8_2->id, 'receipt_number' => 'PR-2026-SC8-002',
            'issued_date' => Carbon::now()->subDays(20), 'fine_amount' => 1000.00,
            'payment_deadline' => Carbon::now()->subDays(17), 'paid_date' => Carbon::now()->subDays(16),
            'paid_amount' => 1000.00, 'payment_status' => 'paid',
            'issued_by' => $officerAKW1->id,
        ]);

        // 3rd offense — court escalation pending
        $rec_sc8_3 = ViolationRecord::create([
            'violator_id' => $v_scenario8->id, 'violation_type_id' => $vtIds['S2-001'],
            'sub_city_id' => $addisKetema->id, 'woreda_id' => $akW1->id,
            'specific_location' => 'ፒያሳ ገበያ ደቡብ በር',
            'violation_date' => Carbon::now()->subDays(2), 'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 2',
            'fine_amount' => 500.00, 'repeat_offense_count' => 2, 'status' => 'penalty_issued',
            'action_taken' => '3ኛ ጥፋት! ተደጋጋሚ ደንብ ተላላፊ። ቅጣት ተሰጥቷል። ለፍርድ ቤት ሊቀርብ ይችላል።',
            'investigation_notes' => 'ተደጋጋሚ ደንብ ተላላፊ - 3ኛ ጊዜ በተመሳሳይ ቦታ ተመሳሳይ ጥፋት።',
            'reported_by' => $officerAKW1->id, 'verified_by' => $supervisorAK->id,
        ]);
        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc8_3->id, 'receipt_number' => 'PR-2026-SC8-003',
            'issued_date' => Carbon::now()->subDays(2), 'fine_amount' => 500.00,
            'payment_deadline' => Carbon::now()->addDays(1),
            'payment_status' => 'pending',
            'issued_by' => $officerAKW1->id,
        ]);

        // ============================================================
        // SCENARIO 9-14: FULL ASSET LIFECYCLE
        // ============================================================
        $this->command->info('--- Scenario 9-14: Full Asset Lifecycle ---');

        $v_scenario9 = Violator::create([
            'type' => 'organization', 'full_name_am' => 'ህንፃ ግንባታ ድርጅት', 'full_name_en' => 'Hinsa Construction',
            'phone' => '0115999999', 'id_number' => 'SC9-BIZ-001',
            'sub_city_id' => $arada->id, 'woreda_id' => $arW1->id,
            'specific_location' => 'አራት ኪሎ ግንባታ ቦታ',
        ]);

        $rec_sc9 = ViolationRecord::create([
            'violator_id' => $v_scenario9->id, 'violation_type_id' => $vtIds['S1-002'],
            'sub_city_id' => $arada->id, 'woreda_id' => $arW1->id,
            'block' => 'C-8', 'specific_location' => 'አራት ኪሎ ግንባታ ቦታ',
            'violation_date' => Carbon::now()->subDays(25), 'violation_time' => '07:30:00',
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'fine_amount' => 0.00, 'status' => 'penalty_issued',
            'action_taken' => 'ንብረት ተወርሷል። ማስጠንቀቂያ ተሰጥቷል።',
            'investigation_notes' => 'ህገ-ወጥ መሬት ማስፋፋት። ብረት፣ ሲሚንቶ፣ አለት ተወርሷል። የሚበላሽ ምግብም ተወርሷል።',
            'reported_by' => $officerAradaW1->id, 'verified_by' => $supervisorArada->id,
        ]);

        WarningLetter::create([
            'violation_record_id' => $rec_sc9->id, 'reference_number' => 'WL-2026-SC9-001',
            'warning_type' => 'three_day',
            'issued_date' => Carbon::now()->subDays(25), 'deadline' => Carbon::now()->subDays(22),
            'complied' => false,
            'escalated_to_task_force' => true, 'escalation_date' => Carbon::now()->subDays(22),
            'regulation_number' => 'ደንብ 150/2023', 'article' => 'ሰንጠረዥ 1',
            'delivery_method' => 'in_person', 'violator_accepted' => false,
            'issued_by' => $officerAradaW1->id, 'issued_by_officer_2' => $supervisorArada->id,
            'notes' => 'ደንብ ተላላፊው ማስጠንቀቂያ አልተቀበለም። ንብረት ለመውረስ ተወስኗል።',
        ]);

        PenaltyReceipt::create([
            'violation_record_id' => $rec_sc9->id, 'receipt_number' => 'PR-2026-SC9-001',
            'issued_date' => Carbon::now()->subDays(21), 'issued_time' => '08:00:00',
            'fine_amount' => 0.00, 'payment_deadline' => Carbon::now()->subDays(18),
            'payment_status' => 'overdue',
            'issued_by' => $officerAradaW1->id,
        ]);

        // Asset 1: Non-perishable → Sold (60% authority / 40% city finance per Reg 150/2023 Art. 28)
        ConfiscatedAsset::create([
            'violation_record_id' => $rec_sc9->id,
            'description' => 'የግንባታ ብረት 12mm (Construction iron bars 12mm)',
            'quantity' => 100, 'unit' => 'ቁጥር', 'is_perishable' => false,
            'seized_date' => Carbon::now()->subDays(22), 'seizure_receipt_number' => 'SR-2026-SC9-001',
            'seized_by' => $officerAradaW1->id,
            'handover_date' => Carbon::now()->subDays(22), 'received_by' => $supervisorArada->id,
            'estimated_value' => 35000.00,
            'transferred_date' => Carbon::now()->subDays(19), 'transferred_to_sub_city_id' => $arada->id,
            'sold_amount' => 30000.00, 'authority_share' => 18000.00, 'city_finance_share' => 12000.00,
            'status' => 'sold',
            'notes' => '60% ለባለስልጣን (18,000) + 40% ለከተማ ፋይናንስ (12,000)።',
        ]);

        // Asset 2: Non-perishable → Transferred (awaiting sale)
        ConfiscatedAsset::create([
            'violation_record_id' => $rec_sc9->id,
            'description' => 'ሲሚንቶ ከረጢት (Cement bags - Derba brand)',
            'quantity' => 50, 'unit' => 'ከረጢት', 'is_perishable' => false,
            'seized_date' => Carbon::now()->subDays(22), 'seizure_receipt_number' => 'SR-2026-SC9-002',
            'seized_by' => $officerAradaW1->id,
            'handover_date' => Carbon::now()->subDays(22), 'received_by' => $supervisorArada->id,
            'estimated_value' => 22500.00,
            'transferred_date' => Carbon::now()->subDays(19), 'transferred_to_sub_city_id' => $arada->id,
            'status' => 'transferred',
            'notes' => 'ወደ ክ/ከተማ ግምጃ ቤት ተላልፏል። ጨረታ ይጠበቃል።',
        ]);

        // Asset 3: Perishable → Fast-track sale (same day)
        ConfiscatedAsset::create([
            'violation_record_id' => $rec_sc9->id,
            'description' => 'የግንባታ ሰራተኞች ምግብ ቁሳቁስ (Perishable food items)',
            'quantity' => 1, 'unit' => 'ሎት', 'is_perishable' => true,
            'seized_date' => Carbon::now()->subDays(22), 'seizure_receipt_number' => 'SR-2026-SC9-003',
            'seized_by' => $officerAradaW1->id,
            'handover_date' => Carbon::now()->subDays(22), 'received_by' => $supervisorArada->id,
            'estimated_value' => 2000.00,
            'sold_amount' => 1500.00, 'authority_share' => 900.00, 'city_finance_share' => 600.00,
            'status' => 'sold',
            'notes' => 'የሚበላሽ ንብረት - በተመሳሳይ ቀን በወረዳ ደረጃ ተሸጧል።',
        ]);

        // Asset 4: Cannot be sold → Disposed
        ConfiscatedAsset::create([
            'violation_record_id' => $rec_sc9->id,
            'description' => 'የተሰባበረ ብሎኬት (Broken concrete blocks)',
            'quantity' => 200, 'unit' => 'ቁጥር', 'is_perishable' => false,
            'seized_date' => Carbon::now()->subDays(22), 'seizure_receipt_number' => 'SR-2026-SC9-004',
            'seized_by' => $officerAradaW1->id,
            'handover_date' => Carbon::now()->subDays(22), 'received_by' => $supervisorArada->id,
            'estimated_value' => 0.00,
            'disposal_reason' => 'ንብረቱ ተሰብሯል። ለጨረታ ማቅረብ አይቻልም። ኮሚቴ ቃለ ጉባኤ ይዟል።',
            'status' => 'disposed',
            'notes' => 'በአስወጋጅ ኮሚቴ ውሳኔ መሰረት ተወግዷል።',
        ]);

        // Asset 5: Just seized
        ConfiscatedAsset::create([
            'violation_record_id' => $rec_sc9->id,
            'description' => 'የግንባታ አለት (Construction gravel)',
            'quantity' => 10, 'unit' => 'ኩንታል', 'is_perishable' => false,
            'seized_date' => Carbon::now(), 'seizure_receipt_number' => 'SR-2026-SC9-005',
            'seized_by' => $officerAradaW1->id,
            'status' => 'seized',
            'notes' => 'ዛሬ ተወርሷል። ለወረዳ ንብረት ክፍል ማስረከብ ያስፈልጋል።',
        ]);

        // ============================================================
        // SCENARIO 15-16: INTERNAL HR INCIDENTS
        // ============================================================
        $this->command->info('--- Scenario 15-16: Internal HR Incidents ---');

        $employees = Employee::take(3)->get();
        if ($employees->isNotEmpty()) {
            $inc1 = IncidentReport::create([
                'employee_id' => $employees[0]->id,
                'incident_type' => 'misconduct', 'location' => 'Addis Ketema W01 block A-3',
                'incident_date' => Carbon::now()->subDays(15),
                'description' => 'ኦፊሰሩ ያለ ፈቃድ ከስምሪት ቦታው ተገኝቷል። በስምሪት ካርድ ላይ የተመደበው ቦታ ላይ አልነበረም።',
                'status' => 'penalty_assigned',
                'reported_by' => $supervisorAK->id,
            ]);

            PenaltyAssignment::create([
                'incident_report_id' => $inc1->id, 'penalty_type_id' => $penaltyTypes[0]->id,
                'assigned_date' => Carbon::now()->subDays(12), 'due_date' => Carbon::now()->addDays(18),
                'duration_days' => 30, 'status' => 'assigned',
                'notes' => 'የጽሁፍ ማስጠንቀቂያ ተሰጥቷል።',
                'assigned_by' => $supervisorAK->id, 'assigned_to' => $employees[0]->id,
            ]);

            PenaltyAssignment::create([
                'incident_report_id' => $inc1->id, 'penalty_type_id' => $penaltyTypes[4]->id,
                'assigned_date' => Carbon::now()->subDays(12), 'due_date' => Carbon::now()->subDays(1),
                'duration_days' => 1, 'status' => 'completed',
                'notes' => 'የ1 ቀን ደመወዝ ቅነሳ ተፈጽሟል።',
                'assigned_by' => $admin->id, 'assigned_to' => $employees[0]->id,
            ]);

            $inc2 = IncidentReport::create([
                'employee_id' => $employees->count() > 1 ? $employees[1]->id : $employees[0]->id,
                'incident_type' => 'non_compliance', 'location' => 'Arada W01 patrol area',
                'incident_date' => Carbon::now()->subDays(5),
                'description' => 'ለ3 ተከታታይ ቀናት የውሎ ሪፖርት አላቀረበም።',
                'status' => 'in_follow_up',
                'reported_by' => $officerAradaW1->id,
            ]);

            FollowUpAction::create([
                'incident_report_id' => $inc2->id, 'action_type_id' => $actionTypes[0]->id,
                'due_date' => Carbon::now()->subDays(3), 'status' => 'done',
                'completed_at' => Carbon::now()->subDays(3),
                'notes' => 'የቃል ምክር ተሰጥቷል። ኦፊሰሩ ችግሩን ተቀብሏል።',
                'assigned_by' => $supervisorArada->id, 'assigned_to' => $officerAradaW1->id,
            ]);

            FollowUpAction::create([
                'incident_report_id' => $inc2->id, 'action_type_id' => $actionTypes[5]->id,
                'due_date' => Carbon::now()->addDays(7), 'status' => 'pending',
                'notes' => 'ከ1 ሳምንት በኋላ የውሎ ሪፖርት ማቅረብ ተከታታይ ምርመራ።',
                'assigned_by' => $supervisorArada->id, 'assigned_to' => $officerAradaW1->id,
            ]);

            $inc3 = IncidentReport::create([
                'employee_id' => $employees->count() > 2 ? $employees[2]->id : $employees[0]->id,
                'incident_type' => 'attendance', 'location' => 'Bole W01 checkpoint',
                'incident_date' => Carbon::now()->subDays(3),
                'description' => 'በዚህ ወር 5 ጊዜ ለጠዋት ሽፍት ዘግይቷል። ከ1:45 ፈንታ ከ2:30 በኋላ ይመጣል።',
                'status' => 'reported',
                'reported_by' => $officerBole->id,
            ]);

            FollowUpAction::create([
                'incident_report_id' => $inc3->id, 'action_type_id' => $actionTypes[1]->id,
                'due_date' => Carbon::now()->addDays(1), 'status' => 'in_progress',
                'notes' => 'የጽሁፍ ማስታወቂያ እየተዘጋጀ ነው።',
                'assigned_by' => $officerBole->id, 'assigned_to' => $officerBole->id,
            ]);
        }

        // ============================================================
        // SUMMARY
        // ============================================================
        $this->command->info('');
        $this->command->info('======================================');
        $this->command->info(' PENALTY MODULE SEEDED SUCCESSFULLY');
        $this->command->info('======================================');
        $this->command->info('');
        $this->command->info('SCHEDULES SEEDED (Reg. 150/2023 + Reg. 180/2024):');
        $this->command->info('  Schedule 1 — Illegal Land Seizure & Construction (6 types)');
        $this->command->info('  Schedule 2 — Illegal Waste Disposal (33 types)');
        $this->command->info('  Schedule 3 — Traffic & Road Safety (20 types)');
        $this->command->info('  Schedule 4 — Illegal Outdoor Advertisements (4 types)');
        $this->command->info('  Schedule 5 — Disturbing Activities (12 types)');
        $this->command->info('  Schedule 6 — Illegal Animal Circulation/Slaughtering (6 types)');
        $this->command->info('  Schedule 7 — Riverbank Pollution, Reg. 180/2024 (28 types)');
        $this->command->info('');
        $this->command->info('SCENARIO 2:  Direct Fine → Paid (AK W01)');
        $this->command->info('SCENARIO 3:  Receipt Refused + 3 Witnesses (Arada W01)');
        $this->command->info('SCENARIO 4:  Non-Payment → Court, Fine Doubled (AK W02)');
        $this->command->info('SCENARIO 5a: 3-Day Warning → Complied (Arada W02)');
        $this->command->info('SCENARIO 5b: 3-Day Warning → Not Complied → Penalty (AK W01)');
        $this->command->info('SCENARIO 6:  24-Hour Warning → Task Force Demolition (Bole W01)');
        $this->command->info('SCENARIO 7:  Task Force Escalation - Beyond Capacity (Akaki W01)');
        $this->command->info('SCENARIO 8:  Repeat Offender - 3 violations (AK W01)');
        $this->command->info('SCENARIO 9:  Asset Seizure + Lifecycle (Arada W01)');
        $this->command->info('SCENARIO 15: HR Misconduct → Penalty Assignment');
        $this->command->info('SCENARIO 16: HR Non-Compliance → Follow-up Actions');
        $this->command->info('');
        $this->command->info('DATA COUNTS:');
        $this->command->info('  ' . PenaltySchedule::count() . ' schedules | ' . ViolationType::count() . ' violation types');
        $this->command->info('  ' . Violator::count() . ' violators | ' . ViolationRecord::count() . ' violation records');
        $this->command->info('  ' . WarningLetter::count() . ' warning letters | ' . PenaltyReceipt::count() . ' penalty receipts');
        $this->command->info('  ' . ConfiscatedAsset::count() . ' confiscated assets | ' . IncidentReport::count() . ' incidents');
        $this->command->info('');
        $this->command->info('SCOPING TEST (who sees what in Violation Records):');
        $this->command->info('  Admin [1]:             ALL records');
        $this->command->info('  Officer Test [4]:      AK W01 → SC2, SC5b, SC8(x3) = 5 records');
        $this->command->info('  Officer Boka [5]:      AK W02 → SC4 = 1 record');
        $this->command->info('  Abera Supervisor [10]: AK W01 → SC2, SC5b, SC8(x3) = 5 records');
        $this->command->info('  Arada1 Officer [11]:   Arada W01 → SC3, SC9 = 2 records');
        $this->command->info('  arada01 Officer [20]:  Arada W02 → SC5a = 1 record');
        $this->command->info('  arada supervisor [19]: Arada ALL → SC3, SC5a, SC9 = 3 records');
        $this->command->info('  Olyad Akaki [21]:      Bole W01 → SC6 = 1 record');
        $this->command->info('  Akaki Officer [12]:    Akaki W01 → SC7 = 1 record');
    }
}
