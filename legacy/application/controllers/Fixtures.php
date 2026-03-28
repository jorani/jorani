<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Faker\Factory;

/**
 * Fixtures Generator
 *
 * Can only be executed from CLI:
 * php index.php Fixtures generate
 */
class Fixtures extends CI_Controller
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        parent::__construct();
        // Ensure this script cannot be invoked from the web
        if (!is_cli()) {
            show_error('This script can only be accessed via the command line', 403);
            exit;
        }
        //$config['log_threshold'] = 4;
        $this->faker = Factory::create();
    }

    /**
     * Generate fixtures
     * @return void
     */
    public function generate(): void
    {
        echo "Starting fixture generation...\n";
        // Clean tables (Disable foreign keys temporarily)
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        // Ensure MySQL keeps id = 0
        $this->db->query("SET SESSION sql_mode = CONCAT(@@sql_mode, ',NO_AUTO_VALUE_ON_ZERO')");
        $this->db->truncate('users');
        $this->db->truncate('organization');
        $this->db->truncate('positions');
        $this->db->truncate('contracts');
        $this->db->truncate('types');
        $this->db->truncate('entitleddays');
        $this->db->truncate('leaves');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        echo "Tables truncated.\n";

        // 1. Contracts
        $contracts = [
            [
                'id' => 1,
                'name' => 'Global Contract',
                'startentdate' => '01/01',
                'endentdate' => '12/31',
                'weekly_duration' => 2400,
                'daily_duration' => 480,
                'default_leave_type' => 1
            ],
            [
                'id' => 2,
                'name' => 'French Contract',
                'startentdate' => '06/01',
                'endentdate' => '05/31',
                'weekly_duration' => 2400,
                'daily_duration' => 480,
                'default_leave_type' => 1
            ],
            [
                'id' => 3,
                'name' => 'Part-time',
                'startentdate' => '01/01',
                'endentdate' => '12/31',
                'weekly_duration' => 1200,
                'daily_duration' => 240,
                'default_leave_type' => 1
            ]
        ];
        $this->db->insert_batch('contracts', $contracts);

        // 2. Organization (Departments)
        $orgs = [
            ['id' => 0, 'name' => 'Root LMS', 'parent_id' => -1, 'supervisor' => NULL],
            ['id' => 1, 'name' => 'IT Department', 'parent_id' => 0, 'supervisor' => 1],
            ['id' => 2, 'name' => 'HR Department', 'parent_id' => 0, 'supervisor' => 2],
            ['id' => 3, 'name' => 'Staff', 'parent_id' => 0, 'supervisor' => 1],
            ['id' => 4, 'name' => 'Management', 'parent_id' => 3, 'supervisor' => 1],
            ['id' => 5, 'name' => 'Employees', 'parent_id' => 3, 'supervisor' => 1],
            ['id' => 6, 'name' => 'Branch 1', 'parent_id' => 3, 'supervisor' => 1],
            ['id' => 7, 'name' => 'Dept 1', 'parent_id' => 6, 'supervisor' => 1],
            ['id' => 8, 'name' => 'Dept 2', 'parent_id' => 6, 'supervisor' => 1],
            ['id' => 9, 'name' => 'Branch 2', 'parent_id' => 3, 'supervisor' => 1],
            ['id' => 10, 'name' => 'Dept 1', 'parent_id' => 9, 'supervisor' => 1],
            ['id' => 11, 'name' => 'Dept 2', 'parent_id' => 9, 'supervisor' => 1],
            ['id' => 12, 'name' => 'Branch 3', 'parent_id' => 3, 'supervisor' => 1],
            ['id' => 13, 'name' => 'Dept 1', 'parent_id' => 12, 'supervisor' => 1],
            ['id' => 14, 'name' => 'Dept 2', 'parent_id' => 12, 'supervisor' => 1],
        ];
        $this->db->insert_batch('organization', $orgs);

        // 3. Positions
        $positions = [
            ['id' => 1, 'name' => 'Director', 'description' => 'Company or Department Director'],
            ['id' => 2, 'name' => 'Manager', 'description' => 'Team Manager'],
            ['id' => 3, 'name' => 'Employee', 'description' => 'Regular Employee']
        ];
        $this->db->insert_batch('positions', $positions);

        // 4. Types of Leaves
        $types = [
            ['id' => 0, 'name' => 'Compensate', 'deduct_days_off' => 0],
            ['id' => 1, 'name' => 'Paid leave', 'deduct_days_off' => 0],
            ['id' => 2, 'name' => 'Sick leave', 'deduct_days_off' => 0],
            ['id' => 3, 'name' => 'Paternity leave', 'deduct_days_off' => 1],
            ['id' => 4, 'name' => 'Maternity leave', 'deduct_days_off' => 1],
            ['id' => 5, 'name' => 'Unpaid leave', 'deduct_days_off' => 1],
            ['id' => 6, 'name' => 'RTTE', 'deduct_days_off' => 0],
            ['id' => 7, 'name' => 'RTTS', 'deduct_days_off' => 0]
        ];
        $this->db->insert_batch('types', $types);

        echo "Dictionaries and organization inserted.\n";

        // 5. Users
        // Using Users_model to ensure passwords and random hashes are correctly generated
        // insertUserByApi(firstname, lastname, login, email, password, role, manager, organization, contract, position, datehired, identifier, language, timezone, ldap_path, active, country, calendar, userProperties, picture)
        $users = [
            ['id' => 1, 'firstname' => 'Admin', 'lastname' => 'ADMINISTRATOR', 'login' => 'jorani', 'email' => 'jorani@example.org', 'password' => '$2a$08$My0FoP4zalOiS2OM6rsCz.XXozSW1UiqRq4zKup/CC/kOxXDCs9Zm', 'role' => 1, 'manager' => 1, 'organization' => 0, 'contract' => 1, 'position' => 1, 'language' => 'en', 'active' => 1, 'timezone' => 'Europe/Paris'],
            ['id' => 2, 'firstname' => 'John', 'lastname' => 'DOE', 'login' => 'jdoe', 'email' => 'jdoe@example.org', 'password' => '$2a$08$TU9Syb0FTqcKGwAg5AtH.OLThxcR3p.1gKEcorxvbuqKWTyHQRI7y', 'role' => 1, 'manager' => 1, 'organization' => 0, 'contract' => 1, 'position' => 1, 'language' => 'en', 'active' => 1, 'timezone' => 'Europe/Paris']
        ];
        for ($ii = 0; $ii < 100; $ii++) {
            $firstname = $this->faker->firstName;
            $lastname = $this->faker->lastName;
            $login = strtolower(substr($firstname, 0, 1) . $lastname);
            $email = "{$login}@example.org";
            $users[] = [
                'id' => $ii + 3,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'login' => $login,
                'email' => $email,
                'password' => '$2a$08$hTRW29m7Nyf.fppS32KfquodzgoNSH2ycVQGVHajq3G2.eJznwrnq',
                'role' => 2,
                'manager' => $this->faker->numberBetween(1, 2),
                'organization' => $this->faker->numberBetween(3, 14),
                'contract' => 1,
                'position' => 1,
                'language' => 'en',
                'active' => 1,
                'timezone' => 'Europe/Paris'
            ];
        }
        $this->db->insert_batch('users', $users);
        echo "Users inserted. (Passwords: jorani for jorani (admin), password for others)\n";

        // 6. Entitled Days (Balances)
        $entitledDays = [];
        $currentYear = (int) date('Y');
        // Loop from current year - 3 to current year
        for ($year = $currentYear - 3; $year <= $currentYear; $year++) {
            $entitledDays[] = [
                'contract' => 1,        // Global to contract
                'employee' => null,
                'startdate' => $year . '-01-01',
                'enddate' => $year . '-12-31',
                'type' => 1,
                'days' => 25.00,
                'description' => "Annual base allowance {$year}"
            ];
            $entitledDays[] = [
                'contract' => 1,        // Global to contract
                'employee' => null,
                'startdate' => $year . '-01-01',
                'enddate' => $year . '-12-31',
                'type' => 6,
                'days' => 5.00,
                'description' => "Annual base allowance RTTE {$year}"
            ];
            $entitledDays[] = [
                'contract' => 1,        // Global to contract
                'employee' => null,
                'startdate' => $year . '-01-01',
                'enddate' => $year . '-12-31',
                'type' => 7,
                'days' => 10.00,
                'description' => "Annual base allowance RTTS {$year}"
            ];
        }
        $this->db->insert_batch('entitleddays', $entitledDays);
        echo "Entitled days inserted. (25 days of paid leave, 5 days of RTTE, 10 days of RTTS per year)\\n";

        // 7. Leaves (Requests)
        // Status: 1=Planned, 2=Requested, 3=Accepted, 4=Rejected
        for ($ii = 0; $ii < 1000; $ii++) {
            // Generate a start date between -3 years and +1 year from today
            $startDate = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-3 years', '+1 year')
            )->setTime(0, 0, 0);

            // Generate an end date with a maximum span of 20 calendar days
            $daysToAdd = $this->faker->numberBetween(0, 19);
            $endDate = $startDate->modify('+' . $daysToAdd . ' days');

            // Compute the number of business days (Monday to Friday), inclusive
            $duration = $this->countBusinessDays($startDate, $endDate);

            $leaves[] = [
                'startdate' => $startDate->format('Y-m-d'),
                'enddate' => $endDate->format('Y-m-d'),
                'status' => $this->faker->numberBetween(LMS_PLANNED, LMS_CANCELED),
                'employee' => $this->faker->numberBetween(1, 102),
                'cause' => $this->faker->words(5, true),
                'startdatetype' => 'Morning',
                'enddatetype' => 'Afternoon',
                'duration' => $duration,
                'type' => $this->faker->randomElement([1, 6, 7])
            ];
        }
        $this->db->insert_batch('leaves', $leaves);
        echo "Leaves inserted. (1000 leaves)\n";

        // 8. Overtime requests
        for ($ii = 0; $ii < 100; $ii++) {
            // Generate a start date between -3 years and +1 year from today
            $startDate = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-3 years', '+1 year')
            )->setTime(0, 0, 0);

            $overtimes[] = [
                'date' => $startDate->format('Y-m-d'),
                'status' => $this->faker->numberBetween(LMS_PLANNED, LMS_REJECTED),
                'employee' => $this->faker->numberBetween(1, 102),
                'cause' => $this->faker->words(5, true),
                'duration' => $this->faker->randomFloat(2, 0.25, 1)
            ];
        }
        $this->db->insert_batch('overtime', $overtimes);
        echo "Overtime requests inserted. (100 overtimes)\n";

        // 9. Day offs (Weekends)
        $dayoffs = [];
        $currentYear = (int) date('Y');
        $start = new DateTimeImmutable($currentYear . '-01-01');
        $end = new DateTimeImmutable($currentYear . '-12-31');

        for ($date = $start; $date <= $end; $date = $date->modify('+1 day')) {
            $dayOfWeek = (int) $date->format('N'); // 6 = Saturday, 7 = Sunday

            if ($dayOfWeek >= 6) {
                $dayoffs[] = [
                    'contract' => 1, // Global contract
                    'date' => $date->format('Y-m-d'),
                    'type' => 1, // Full day
                    'title' => $dayOfWeek === 6 ? 'Saturday' : 'Sunday'
                ];
                $dayoffs[] = [
                    'contract' => 2,
                    'date' => $date->format('Y-m-d'),
                    'type' => 1, // Full day
                    'title' => $dayOfWeek === 6 ? 'Saturday' : 'Sunday'
                ];
                $dayoffs[] = [
                    'contract' => 3,
                    'date' => $date->format('Y-m-d'),
                    'type' => 1, // Full day
                    'title' => $dayOfWeek === 6 ? 'Saturday' : 'Sunday'
                ];
            }
        }
        $this->db->insert_batch('dayoffs', $dayoffs);
        echo "Weekends inserted into dayoffs (" . count($dayoffs) . " records)\n";


        echo "Entitlements and leaves inserted.\n";
        echo "Fixtures generated successfully!\n";
    }

    /**
     * Count business days (Monday to Friday) between two dates, inclusive.
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return float
     */
    private function countBusinessDays(\DateTimeInterface $startDate, \DateTimeInterface $endDate): float
    {
        if ($endDate < $startDate) {
            return 0.0;
        }

        $businessDays = 0;
        $currentDate = \DateTimeImmutable::createFromInterface($startDate);
        $lastDate = \DateTimeImmutable::createFromInterface($endDate);

        while ($currentDate <= $lastDate) {
            $dayOfWeek = (int) $currentDate->format('N'); // 1 = Monday, 7 = Sunday

            if ($dayOfWeek <= 5) {
                $businessDays++;
            }

            $currentDate = $currentDate->modify('+1 day');
        }

        return (float) $businessDays;
    }
}
