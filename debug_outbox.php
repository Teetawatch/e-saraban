<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== E-Saraban Outbox Debug Tool ===" . PHP_EOL . PHP_EOL;

// 1. List all departments
echo "--- All Departments ---" . PHP_EOL;
$depts = \App\Models\Department::all();
foreach($depts as $d) {
    echo "ID: " . $d->id . " - " . $d->name . PHP_EOL;
}
echo PHP_EOL;

// 2. List all users with their department
echo "--- All Users ---" . PHP_EOL;
$users = \App\Models\User::with('department')->get();
foreach($users as $u) {
    $deptName = $u->department ? $u->department->name : 'N/A (department_id: ' . $u->department_id . ')';
    echo "User ID: " . $u->id . " | " . $u->name . " | Email: " . $u->email . " | Dept: " . $deptName . PHP_EOL;
}
echo PHP_EOL;

// 3. List last 10 documents with department and creator  
echo "--- Last 10 Documents (For Outbox) ---" . PHP_EOL;
$docs = \App\Models\Document::with(['department', 'user', 'routes'])
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
    
foreach($docs as $d) {
    $hasReceiveAction = $d->routes->where('action', 'receive')->count() > 0;
    $receiveStatus = $hasReceiveAction ? 'HAS receive action (hidden from outbox)' : 'No receive action (visible in outbox)';
    echo "Doc ID: " . $d->id . " | Title: " . $d->title . PHP_EOL;
    echo "   -> Document Dept: " . $d->department->name . " (ID: " . $d->department_id . ")" . PHP_EOL;
    echo "   -> Created by: " . $d->user->name . " (User Dept ID: " . $d->user->department_id . ")" . PHP_EOL;
    echo "   -> " . $receiveStatus . PHP_EOL;
    echo PHP_EOL;
}

echo "=== Debug Complete ===" . PHP_EOL;
