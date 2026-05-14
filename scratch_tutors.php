<?php
$users = App\Models\User::role('Tutor')->get();
foreach($users as $user) {
    if(!$user->tutor) {
        $user->tutor()->create([]);
    }
}
echo "Tutors backfilled successfully\n";
