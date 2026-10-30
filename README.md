# Online Queue Management Laravel

Standalone Laravel queue app: service locations/categories/counters, daily queue numbering, public queue display, ticket lookup, protected staff controls, and reports.

## Local setup (Windows PowerShell)

```powershell
Copy-Item .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Seeded demo location, categories, and counters contain no customer data. Registration creates a `customer` account by default; public queue registration also works without login. To grant staff access, create a normal account, then promote it through trusted Tinker/DB administration:

```powershell
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'staff@example.com')->firstOrFail();
$user->forceFill(['role' => 'staff'])->save();
```

Only `staff` and `admin` accounts can use staff dashboards, call/recall/serve/complete/cancel controls, or reports. Never expose role assignment through public registration.

## Queue behavior

- Daily numbering is per location and service category: e.g. `G-001`; it resets by the application timezone date. The sequence row is locked and incremented in the registration transaction, with unique DB constraints as the final duplicate guard. Use a transactional production database that supports row-level locking; SQLite is suitable for development/tests, not high-contention concurrent production use.
- Public displays show queue number, status, counter, and waiting count only; customer names/phones are never shown. Private ticket URLs contain random lookup tokens; keep them private.
- Staff transitions: waiting → called → serving → completed; waiting/called can be cancelled; called tickets can be recalled. Invalid transitions are rejected and audited.
- Rate-limited public registration is enabled. CAPTCHA/anti-abuse, SMS notifications, estimated waits, QR codes, and real-time push updates are not included. Public display refreshes every 15 seconds.
- No GPS or external payment features are used.

## Validation

```powershell
php artisan migrate:fresh --seed
php artisan test --compact
php artisan view:cache
npm run build
composer validate --no-check-publish
composer audit
npm audit --omit=dev
```

Full `npm audit` reports three moderate advisories in the Tailwind 3/PostCSS development toolchain (`postcss-selector-parser` via `postcss-nested`); npm's suggested remediation upgrades Tailwind to v4 (breaking). Production dependency audit is clean. Reassess/migrate the dev toolchain before changing Tailwind; do not apply `npm audit fix --force` blindly.

For production configure HTTPS, a transactional relational DB, backups, rate limiting appropriate to traffic, monitoring, data-retention rules, and least-privilege staff accounts. Do not commit `.env`, customer records, `vendor`, `node_modules`, or build output. MIT license.
