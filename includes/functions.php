<?php
// Shared helper functions for the whole site.
require_once __DIR__ . '/db.php';

// Escape any value before printing it in HTML (prevents XSS).
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Format a price with the site currency.
function money($amount): string {
    return number_format((float)$amount, 0) . ' ' . CURRENCY;
}

// One-time flash messages (shown after redirects).
function flash_set(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}
function flash_get(): ?array {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// ============================================
// Type registry: describes each bookable service
// in one place, so listings / details / booking /
// admin can all work with any type generically.
// ============================================
function type_config(?string $type = null) {
    $map = [
        'flight' => [
            'table' => 'flights', 'label' => 'Flight', 'plural' => 'Flights', 'icon' => '✈️',
            'unit' => 'person', 'qty_label' => 'Passengers',
            'fields' => [
                'from_city' => 'From', 'to_city' => 'To', 'flight_date' => 'Date',
                'airline' => 'Airline', 'price' => 'Price (EGP)', 'seats' => 'Seats',
            ],
        ],
        'hotel' => [
            'table' => 'hotels', 'label' => 'Hotel', 'plural' => 'Hotels', 'icon' => '🏨',
            'unit' => 'night', 'qty_label' => 'Nights',
            'fields' => [
                'city' => 'City', 'name' => 'Hotel name',
                'price_per_night' => 'Price per night (EGP)', 'rating' => 'Rating (0-5)',
                'description' => 'Description',
            ],
        ],
        'home' => [
            'table' => 'homes', 'label' => 'Hosted Home', 'plural' => 'Hosted Homes', 'icon' => '🏡',
            'unit' => 'night', 'qty_label' => 'Nights',
            'fields' => [
                'city' => 'City', 'country' => 'Country', 'host_name' => 'Host name',
                'title' => 'Title', 'experience_description' => 'Cultural experience',
                'price_per_night' => 'Price per night (EGP)',
            ],
        ],
        'restaurant' => [
            'table' => 'restaurants', 'label' => 'Restaurant', 'plural' => 'Restaurants', 'icon' => '🍽️',
            'unit' => 'person', 'qty_label' => 'Guests',
            'fields' => [
                'city' => 'City', 'name' => 'Restaurant name', 'cuisine' => 'Cuisine',
                'avg_price' => 'Average price per person (EGP)', 'description' => 'Description',
            ],
        ],
        'attraction' => [
            'table' => 'attractions', 'label' => 'Attraction', 'plural' => 'Attractions', 'icon' => '🏛️',
            'unit' => 'person', 'qty_label' => 'Tickets',
            'fields' => [
                'city' => 'City', 'name' => 'Attraction name',
                'ticket_price' => 'Ticket price (EGP)', 'description' => 'Description',
            ],
        ],
    ];
    if ($type === null) return $map;
    return $map[$type] ?? null;
}

// Column holding the price differs per type.
function price_column(string $type): string {
    return [
        'flight' => 'price',
        'hotel' => 'price_per_night',
        'home' => 'price_per_night',
        'restaurant' => 'avg_price',
        'attraction' => 'ticket_price',
    ][$type];
}

// Display helpers: how each type looks on cards and pages.
function item_title(string $type, array $row): string {
    switch ($type) {
        case 'flight': return $row['from_city'] . ' → ' . $row['to_city'];
        case 'home':   return $row['title'];
        default:       return $row['name'];
    }
}
function item_price(string $type, array $row): float {
    return (float)$row[price_column($type)];
}
function item_location(string $type, array $row): string {
    switch ($type) {
        case 'flight': return $row['airline'] . ' · ' . $row['flight_date'];
        case 'home':   return $row['city'] . ', ' . $row['country'];
        default:       return $row['city'] ?? '';
    }
}
function item_description(string $type, array $row): string {
    switch ($type) {
        case 'flight': return $row['seats'] . ' seats available';
        case 'home':   return $row['experience_description'] ?? '';
        default:       return $row['description'] ?? '';
    }
}

// List items of a type with optional search (q) and max price filters.
function list_items(string $type, array $filters = []): array {
    $cfg = type_config($type);
    $where = [];
    $params = [];

    $q = trim($filters['q'] ?? '');
    if ($q !== '') {
        $like = '%' . $q . '%';
        if ($type === 'flight') {
            $where[] = '(to_city LIKE ? OR from_city LIKE ? OR airline LIKE ?)';
            $params = array_merge($params, [$like, $like, $like]);
        } elseif ($type === 'home') {
            $where[] = '(city LIKE ? OR country LIKE ? OR title LIKE ? OR host_name LIKE ?)';
            $params = array_merge($params, [$like, $like, $like, $like]);
        } else {
            $where[] = '(city LIKE ? OR name LIKE ?)';
            $params = array_merge($params, [$like, $like]);
        }
    }

    $max = $filters['max_price'] ?? '';
    if ($max !== '' && is_numeric($max)) {
        $where[] = price_column($type) . ' <= ?';
        $params[] = $max;
    }

    $sql = 'SELECT * FROM ' . $cfg['table'];
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY ' . price_column($type) . ' ASC';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Fetch one item by type and id.
function get_item(string $type, int $id): ?array {
    $cfg = type_config($type);
    if (!$cfg) return null;
    $stmt = db()->prepare('SELECT * FROM ' . $cfg['table'] . ' WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

// Render one card for listing pages.
function render_card(string $type, array $row): void {
    $cfg = type_config($type);
    $unitLabel = $cfg['unit'] === 'night' ? 'per night' : 'per person';
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="card item-card h-100 shadow-sm">
            <div class="card-icon"><?= $cfg['icon'] ?></div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= e(item_title($type, $row)) ?></h5>
                <div class="text-muted small mb-2"><?= e(item_location($type, $row)) ?></div>
                <p class="card-text small flex-grow-1"><?= e(mb_strimwidth(item_description($type, $row), 0, 140, '...')) ?></p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <strong class="text-primary"><?= money(item_price($type, $row)) ?></strong>
                    <span class="text-muted small"><?= $unitLabel ?></span>
                </div>
                <a href="details.php?type=<?= e($type) ?>&id=<?= (int)$row['id'] ?>" class="btn btn-primary btn-sm mt-3">View & Book</a>
            </div>
        </div>
    </div>
    <?php
}
