<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

User::role('Estudiante')->each(function($user) {
    if (!$user->student) {
        $user->student()->create([]);
    }
});

echo "Done.\n";
