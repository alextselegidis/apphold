<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\Observer;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * DemoSeeder
 *
 * Generates realistic demo data for a fictitious logistics company called
 * "Northwind Logistics" that runs a number of internal systems and public
 * websites monitored by Apphold.
 *
 * IMPORTANT: This seeder is intentionally NOT registered inside
 * DatabaseSeeder.php so that it never runs during regular `php artisan db:seed`
 * or `php artisan migrate --seed` calls. It must be invoked explicitly:
 *
 *   php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    /**
     * Default password assigned to every demo user.
     * Documented in README.md so the dataset is easy to log into.
     */
    private const DEMO_PASSWORD = '12345678';

    /**
     * Allowed incident types as defined in the create_incidents migration.
     */
    private const INCIDENT_TYPES = ['site_down', 'ssl_error', 'timeout'];

    /**
     * Allowed incident statuses as defined in the create_incidents migration.
     */
    private const INCIDENT_STATUSES = ['new', 'ignored', 'fixing', 'fixed'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Wrap everything in a transaction so a partial failure does not leave
        // the database in an inconsistent state.
        DB::transaction(function (): void {
            $this->command->info('Seeding demo data for "Northwind Logistics"...');

            // The application scopes observers, tags and incidents to the
            // currently logged-in user (see ObserversController, TagsController,
            // IncidentsController, DashboardController). To make sure the demo
            // data is visible immediately after login, every observer, tag and
            // incident is owned by the default `admin@example.org` account
            // (created by the existing migration with password `12345678`).
            $owner = $this->resolveOwner();

            $users = $this->seedUsers();
            $projects = $this->seedProjects($users);
            $tags = $this->seedTags($owner);
            $observers = $this->seedObservers($owner, $users, $projects, $tags);
            $this->seedIncidents($observers, $owner, $users);

            $this->command->info('Demo data seeded successfully.');
            $this->command->line('Login with admin@example.org (or any demo user) using password: ' . self::DEMO_PASSWORD);
        });
    }

    /**
     * Resolve the user that will own all visible demo data.
     *
     * Prefers the default `admin@example.org` user that ships with the app
     * (created by 0001_01_01_000003_insert_default_admin_row_to_users_table).
     * Falls back to creating it if the migration was not run.
     */
    private function resolveOwner(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@example.org'],
            [
                'name' => 'Admin',
                'password' => Hash::make(self::DEMO_PASSWORD),
                'role' => RoleEnum::ADMIN->value,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }

    /**
     * Create the staff working at the logistics company.
     *
     * One admin (Head of IT/Ops) plus a small team of engineers and operators.
     * `firstOrCreate` is used on the unique email column so the seeder can
     * be re-run safely without producing duplicates.
     *
     * @return array<string, User>
     */
    private function seedUsers(): array
    {
        // Each demo account uses an email of the form `{firstname}@example.org`
        // and the visible `name` field is the user's first name only, as
        // requested for the demo dataset.
        $definitions = [
            // Admin – owns the Apphold instance for the company.
            ['name' => 'Helena', 'role' => RoleEnum::ADMIN->value],
            // SRE / DevOps engineers – on-call for the monitored systems.
            ['name' => 'Marcus', 'role' => RoleEnum::USER->value],
            ['name' => 'Priya',  'role' => RoleEnum::USER->value],
            // Backend developer maintaining the internal APIs.
            ['name' => 'Diego',  'role' => RoleEnum::USER->value],
            // Frontend developer maintaining the public websites.
            ['name' => 'Sofia',  'role' => RoleEnum::USER->value],
            // Operations manager – mostly reads dashboards, can ack incidents.
            ['name' => 'Anika',  'role' => RoleEnum::USER->value],
            // Deactivated former employee – useful to demo the is_active flag.
            ['name' => 'Tom',    'role' => RoleEnum::USER->value, 'is_active' => false],
        ];

        $users = [];

        foreach ($definitions as $data) {
            // Email is derived from the first name to satisfy the demo rule
            // "every email must end with @example.org and the name must match
            // the first name of the user".
            $email = strtolower($data['name']) . '@example.org';

            $users[$email] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'password' => Hash::make(self::DEMO_PASSWORD),
                    'role' => $data['role'],
                    'is_active' => $data['is_active'] ?? true,
                    'email_verified_at' => now(),
                ],
            );
        }

        return $users;
    }

    /**
     * Create the high-level "projects" that group monitored systems.
     *
     * Each project is associated with the users responsible for it via the
     * project_user pivot table.
     *
     * @param  array<string, User>  $users
     * @return array<string, Project>
     */
    private function seedProjects(array $users): array
    {
        $admin = $users['helena@example.org'];
        $marcus = $users['marcus@example.org'];
        $priya = $users['priya@example.org'];
        $diego = $users['diego@example.org'];
        $sofia = $users['sofia@example.org'];
        $anika = $users['anika@example.org'];

        $definitions = [
            'Public Websites' => [$admin, $sofia, $anika],
            'Customer Portal' => [$admin, $sofia, $diego, $marcus],
            'Driver Mobile Backend' => [$admin, $diego, $priya],
            'Warehouse Management' => [$admin, $marcus, $priya, $anika],
            'Tracking & Telematics' => [$admin, $marcus, $priya],
            'Internal Tooling' => [$admin, $diego, $marcus],
        ];

        $projects = [];

        foreach ($definitions as $name => $members) {
            // firstOrCreate keeps the seeder idempotent on the project name.
            $project = Project::firstOrCreate(['name' => $name]);

            // Sync the membership pivot table without detaching unrelated rows.
            $project->observers(); // touch relation to keep IDE hints happy
            $memberIds = array_map(static fn(User $u) => $u->id, $members);

            DB::table('project_user')
                ->where('project_id', $project->id)
                ->whereIn('user_id', $memberIds)
                ->delete();

            foreach ($memberIds as $memberId) {
                DB::table('project_user')->insert([
                    'project_id' => $project->id,
                    'user_id' => $memberId,
                ]);
            }

            $projects[$name] = $project;
        }

        return $projects;
    }

    /**
     * Create a vocabulary of tags that engineers can attach to observers.
     *
     * Tags belong to the user that created them and are scoped per-user in
     * the UI (see TagsController). To make sure the seeded tags are visible
     * after logging in, every tag is owned by the demo `$owner` account.
     *
     * @return array<string, Tag>
     */
    private function seedTags(User $owner): array
    {
        $names = [
            'production',
            'staging',
            'public',
            'internal',
            'api',
            'website',
            'critical',
            'eu-region',
            'us-region',
            'mobile-backend',
            'third-party',
        ];

        $tags = [];

        foreach ($names as $name) {
            // Scope the lookup by both name AND user_id so the tag is
            // attached to the demo owner even if a tag with the same name
            // exists for a different user.
            $tags[$name] = Tag::firstOrCreate(
                ['name' => $name, 'user_id' => $owner->id],
            );
        }

        return $tags;
    }

    /**
     * Create the actual monitored endpoints (observers) – the heart of the demo.
     *
     * Each observer represents a website or HTTP service the company wants
     * Apphold to keep an eye on. Observers are scoped per-user in the UI
     * (see ObserversController), so every observer is owned by `$owner`.
     * The other team members are still referenced through the `notes`
     * field for narrative purposes.
     *
     * @param  array<string, User>  $users
     * @param  array<string, Project>  $projects
     * @param  array<string, Tag>  $tags
     * @return array<int, Observer>
     */
    private function seedObservers(User $owner, array $users, array $projects, array $tags): array
    {
        $admin = $users['helena@example.org'];
        $marcus = $users['marcus@example.org'];
        $priya = $users['priya@example.org'];
        $diego = $users['diego@example.org'];
        $sofia = $users['sofia@example.org'];

        // Comma separated email list used for outage notifications.
        $opsEmails = 'ops@example.org,oncall@example.org';
        $webEmails = 'web@example.org,marketing@example.org';
        $devEmails = 'dev@example.org';

        // Each entry: [project_key, owner_user, title, url, interval, emails,
        //              notes, is_active, with_ssl_verification, [tag_keys]]
        $definitions = [
            // ----- Public Websites -----
            [
                'Public Websites', $sofia,
                'Corporate Marketing Site',
                'https://www.example.org',
                60, $webEmails,
                'Main marketing website served from the EU CDN edge.',
                true, true,
                ['production', 'public', 'website', 'eu-region', 'critical'],
            ],
            [
                'Public Websites', $sofia,
                'Careers Portal',
                'https://careers.example.org',
                300, $webEmails,
                'Job listings and applications. Lower priority than the main site.',
                true, true,
                ['production', 'public', 'website'],
            ],
            [
                'Public Websites', $sofia,
                'Status Page',
                'https://status.example.org',
                60, $opsEmails,
                'Externally hosted status page; used to notify customers about outages.',
                true, true,
                ['production', 'public', 'third-party', 'critical'],
            ],
            [
                'Public Websites', $sofia,
                'Blog',
                'https://blog.example.org',
                300, $webEmails,
                'WordPress blog hosted on a separate VPS.',
                true, true,
                ['production', 'public', 'website'],
            ],

            // ----- Customer Portal -----
            [
                'Customer Portal', $diego,
                'Customer Portal (Web)',
                'https://portal.example.org',
                60, $opsEmails,
                'Where shippers create bookings, track shipments and download invoices.',
                true, true,
                ['production', 'public', 'website', 'critical'],
            ],
            [
                'Customer Portal', $diego,
                'Customer Portal API',
                'https://api.portal.example.org/health',
                60, $opsEmails,
                'JSON health endpoint backing the customer portal.',
                30, true,
                ['production', 'api', 'critical'],
            ],
            [
                'Customer Portal', $diego,
                'Customer Portal (Staging)',
                'https://staging.portal.example.org',
                300, $devEmails,
                'Staging environment for QA before production releases.',
                true, false,
                ['staging', 'website', 'internal'],
            ],

            // ----- Driver Mobile Backend -----
            [
                'Driver Mobile Backend', $priya,
                'Driver App API',
                'https://api.drivers.example.org/health',
                30, $opsEmails,
                'Backend used by the driver smartphone app for routes and proof-of-delivery.',
                true, true,
                ['production', 'api', 'mobile-backend', 'critical'],
            ],
            [
                'Driver Mobile Backend', $priya,
                'Push Notification Gateway',
                'https://push.drivers.example.org/ping',
                60, $opsEmails,
                'Wrapper around APNs/FCM. Outages delay job dispatch notifications.',
                true, true,
                ['production', 'api', 'mobile-backend', 'third-party'],
            ],

            // ----- Warehouse Management -----
            [
                'Warehouse Management', $marcus,
                'WMS Hamburg',
                'https://wms-ham.example.org',
                120, $opsEmails,
                'Warehouse Management System for the Hamburg distribution center.',
                true, true,
                ['production', 'internal', 'eu-region', 'critical'],
            ],
            [
                'Warehouse Management', $marcus,
                'WMS Rotterdam',
                'https://wms-rtm.example.org',
                120, $opsEmails,
                'Warehouse Management System for the Rotterdam distribution center.',
                true, true,
                ['production', 'internal', 'eu-region', 'critical'],
            ],
            [
                'Warehouse Management', $marcus,
                'WMS Chicago',
                'https://wms-ord.example.org',
                120, $opsEmails,
                'Warehouse Management System for the Chicago hub.',
                true, true,
                ['production', 'internal', 'us-region', 'critical'],
            ],
            [
                'Warehouse Management', $marcus,
                'Label Printer Service',
                'https://print.warehouse.example.org/healthz',
                60, $opsEmails,
                'Internal microservice that renders shipping labels for forklift terminals.',
                true, false,
                ['production', 'internal', 'api'],
            ],

            // ----- Tracking & Telematics -----
            [
                'Tracking & Telematics', $priya,
                'GPS Ingest Endpoint',
                'https://ingest.telematics.example.org/health',
                30, $opsEmails,
                'Ingests GPS pings from ~3,000 trucks every few seconds.',
                true, true,
                ['production', 'api', 'critical'],
            ],
            [
                'Tracking & Telematics', $priya,
                'Public Shipment Tracker',
                'https://track.example.org',
                120, $opsEmails,
                'Public-facing parcel tracking page used by end consumers.',
                true, true,
                ['production', 'public', 'website', 'critical'],
            ],

            // ----- Internal Tooling -----
            [
                'Internal Tooling', $diego,
                'Jira (self-hosted)',
                'https://jira.example.org',
                300, $devEmails,
                'Issue tracker used by all engineering teams.',
                true, true,
                ['production', 'internal', 'third-party'],
            ],
            [
                'Internal Tooling', $diego,
                'GitLab (self-hosted)',
                'https://gitlab.example.org',
                300, $devEmails,
                'Source control and CI runners.',
                true, true,
                ['production', 'internal', 'third-party'],
            ],
            [
                'Internal Tooling', $admin,
                'VPN Gateway Health',
                'https://vpn.example.org/health',
                120, $opsEmails,
                'Health endpoint behind the VPN. Used to verify reachability of internal nets.',
                true, true,
                ['production', 'internal', 'critical'],
            ],
            // A deliberately disabled observer to demo the is_active=false case.
            [
                'Internal Tooling', $admin,
                'Legacy Reporting Dashboard',
                'https://legacy-reports.example.org',
                600, $devEmails,
                'Decommissioned reporting tool – kept here for historical context.',
                false, false,
                ['internal'],
            ],
        ];

        $observers = [];

        foreach ($definitions as $row) {
            // $teamMember is the team-member narratively responsible for the
            // system; the actual `user_id` on the observer is always $owner
            // so the data shows up in the demo login's dashboard.
            [
                $projectKey, $teamMember, $title, $url, $interval, $emails,
                $notes, $isActive, $withSslVerification, $tagKeys,
            ] = $row;

            // Prepend the responsible team member to the notes for context.
            $notesWithOwner = "Maintained by {$teamMember->name}. {$notes}";

            // Look up by URL since it's the natural unique key for an observer.
            $observer = Observer::where('url', $url)->first();

            if (! $observer) {
                $observer = new Observer([
                    'user_id' => $owner->id,
                    'title' => $title,
                    'url' => $url,
                    'interval' => $interval,
                    'emails' => $emails,
                    'notes' => $notesWithOwner,
                    'is_active' => $isActive,
                    'with_ssl_verification' => $withSslVerification,
                ]);

                // project_id is on the table but not in $fillable – set directly.
                $observer->project_id = $projects[$projectKey]->id;
                $observer->user_id = $owner->id;
                $observer->save();
            }

            // Sync tags via the observer_tag pivot, idempotently.
            $tagIds = array_map(static fn(string $k) => $tags[$k]->id, $tagKeys);
            $observer->tags()->sync($tagIds);

            $observers[] = $observer;
        }

        return $observers;
    }

    /**
     * Generate a believable history of incidents for the monitored systems.
     *
     * Each observer gets between 2 and 5 incidents to guarantee that the
     * demo dashboard always shows multiple incidents per system. All
     * incidents are dated within the last 30 days, with a portion of them
     * placed in the past 72 hours so "recent activity" widgets are populated.
     * Attributes (type, status, message, status_code) are kept consistent
     * with each other – e.g. an `ssl_error` incident never carries a 200
     * status code.
     *
     * Incidents are scoped per-user in the UI (IncidentsController), so the
     * reporter `user_id` is always the demo `$owner` while the assignee is
     * picked from the wider team for variety.
     *
     * @param  array<int, Observer>  $observers
     * @param  array<string, User>  $users
     */
    private function seedIncidents(array $observers, User $owner, array $users): void
    {
        // Pool of users who can be assigned an incident (active staff only),
        // including the owner so they sometimes appear as the assignee too.
        $assignableUsers = array_values(array_filter(
            array_merge([$owner], array_values($users)),
            static fn(User $u) => $u->is_active,
        ));

        foreach ($observers as $observer) {
            // Disabled observers should not have new incidents in the demo.
            if (! $observer->is_active) {
                continue;
            }

            $incidentCount = random_int(2, 5);

            for ($i = 0; $i < $incidentCount; $i++) {
                $type = self::INCIDENT_TYPES[array_rand(self::INCIDENT_TYPES)];
                $status = self::INCIDENT_STATUSES[array_rand(self::INCIDENT_STATUSES)];
                [$message, $statusCode] = $this->incidentDetailsFor($type, $observer);

                $assignee = $assignableUsers[array_rand($assignableUsers)];

                // Roughly one third of the incidents are placed in the
                // last 72 hours; the rest are scattered across the past
                // 30 days so charts have both fresh and historical data.
                $createdAt = $i % 3 === 0
                    ? now()->subMinutes(random_int(5, 60 * 72))
                    : now()->subMinutes(random_int(60 * 24, 60 * 24 * 30));

                $incident = Incident::create([
                    // Reporter is the demo owner so the incident is visible
                    // in the owner's incident list (which is user-scoped).
                    'user_id' => $owner->id,
                    'observer_id' => $observer->id,
                    'assigned_user_id' => $status === 'new' ? null : $assignee->id,
                    'type' => $type,
                    'status' => $status,
                    'message' => $message,
                    'status_code' => $statusCode,
                ]);

                $incident->created_at = $createdAt;
                $incident->updated_at = $createdAt->copy()->addMinutes(random_int(1, 120));
                $incident->saveQuietly();

                // Add 0–3 comments per incident – conversations between team members.
                $this->seedIncidentComments($incident, $assignableUsers);
            }
        }
    }

    /**
     * Build a realistic message + HTTP status code pair for an incident type.
     *
     * @return array{0: string, 1: int|null}
     */
    private function incidentDetailsFor(string $type, Observer $observer): array
    {
        $host = parse_url($observer->url, PHP_URL_HOST) ?: $observer->url;

        return match ($type) {
            'site_down' => [
                "HTTP request to {$host} returned an error response.",
                [500, 502, 503, 504][array_rand([500, 502, 503, 504])],
            ],
            'ssl_error' => [
                "TLS certificate validation failed for {$host} (chain or expiry issue).",
                null,
            ],
            'timeout' => [
                "Request to {$host} did not complete within the configured timeout window.",
                null,
            ],
        };
    }

    /**
     * Add a short, realistic comment thread to an incident.
     *
     * @param  array<int, User>  $assignableUsers
     */
    private function seedIncidentComments(Incident $incident, array $assignableUsers): void
    {
        $commentCount = random_int(0, 3);

        // A small bank of plausible operational comments. Picked at random
        // and lightly customised per incident so the seeder doesn't produce
        // identical conversations everywhere.
        $bank = [
            'Acknowledged. Looking into the upstream load balancer now.',
            'I can reproduce the error from a curl on the bastion host.',
            'Restarted the affected pod – will keep an eye on the metrics.',
            'Pinged the data-center NOC, they confirmed brief network flapping.',
            'Rolling back the latest deployment, suspected regression.',
            'Certificate has been renewed via Let\'s Encrypt and pushed to the LB.',
            'Increased the timeout from 5s to 10s as a temporary mitigation.',
            'Closing this out – service has been stable for the last hour.',
            'Adding a runbook entry so the next on-call handles this faster.',
            'Marking as ignored – this is a known third-party maintenance window.',
        ];

        $usedKeys = [];

        for ($i = 0; $i < $commentCount; $i++) {
            $author = $assignableUsers[array_rand($assignableUsers)];

            // Pick a comment we haven't already used on this incident.
            do {
                $key = array_rand($bank);
            } while (in_array($key, $usedKeys, true) && count($usedKeys) < count($bank));
            $usedKeys[] = $key;

            $comment = IncidentComment::create([
                'incident_id' => $incident->id,
                'user_id' => $author->id,
                'content' => $bank[$key],
            ]);

            // Keep comments chronologically after the incident was opened.
            $commentedAt = $incident->created_at->copy()->addMinutes(random_int(1, 60 * 8));
            $comment->created_at = $commentedAt;
            $comment->updated_at = $commentedAt;
            $comment->saveQuietly();
        }
    }
}
