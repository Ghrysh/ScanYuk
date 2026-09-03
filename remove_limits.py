import re

with open('app/Http/Controllers/QueueManagementController.php', 'r') as f:
    content = f.read()

# 1. Update index
# Find:
#         $role = strtolower($user->role ?? 'free');
#         $limit = $user->queue_location ?? (QueueLocation::LOCATION_LIMITS[$role] ?? 1);
#         $canCreate = is_null($limit) ? true : ($locations->count() < $limit);
# Replace:
#         $canCreate = true;

content = re.sub(
    r"\$role = strtolower\(\$user->role \?\? 'free'\);\s*\$limit = \$user->queue_location \?\? \(QueueLocation::LOCATION_LIMITS\[\$role\] \?\? 1\);\s*\$canCreate = is_null\(\$limit\) \? true : \(\$locations->count\(\) < \$limit\);",
    "$canCreate = true;",
    content
)

# 2. Update storeLocation
# Find:
#         $role = strtolower($user->role ?? 'free');
#         $limit = $user->queue_location ?? (QueueLocation::LOCATION_LIMITS[$role] ?? 1);
#         
#         $currentCount = QueueLocation::where('user_id', $user->id)->count();
#         if ($limit !== null && $currentCount >= $limit) {
#             return back()->with('error', 'Batas maksimal lokasi antrian untuk paket Anda telah tercapai.')->with('showUpgrade', true);
#         }
# Replace with empty string

content = re.sub(
    r"(\s*\$role = strtolower\(\$user->role \?\? 'free'\);\s*\$limit = \$user->queue_location \?\? \(QueueLocation::LOCATION_LIMITS\[\$role\] \?\? 1\);\s*\$currentCount = QueueLocation::where\('user_id', \$user->id\)->count\(\);\s*if \(\$limit !== null && \$currentCount >= \$limit\) \{\s*return back\(\)->with\('error', 'Batas maksimal lokasi antrian untuk paket Anda telah tercapai\.'\)->with\('showUpgrade', true\);\s*\})",
    "",
    content
)

# 3. Update storeLocation ticket quota check
# Find:
#         if ($request->daily_quota && $user->queue_ticket !== null) {
#             $totalUsedQuota = QueueLocation::where('user_id', $user->id)->sum('daily_quota');
#             if ($totalUsedQuota + $request->daily_quota > $user->queue_ticket) {
#                 return back()->with('error', 'Melebihi total antrian. Total antrian saat ini sisa: ' . max(0, $user->queue_ticket - $totalUsedQuota));
#             }
#         }
# Replace with empty string

content = re.sub(
    r"(\s*if \(\$request->daily_quota && \$user->queue_ticket !== null\) \{\s*\$totalUsedQuota = QueueLocation::where\('user_id', \$user->id\)->sum\('daily_quota'\);\s*if \(\$totalUsedQuota \+ \$request->daily_quota > \$user->queue_ticket\) \{\s*return back\(\)->with\('error', 'Melebihi total antrian\. Total antrian saat ini sisa: ' \. max\(0, \$user->queue_ticket - \$totalUsedQuota\)\);\s*\}\s*\})",
    "",
    content
)

# 4. Update updateLocation ticket quota check
# Find:
#         if ($request->daily_quota && $user->queue_ticket !== null) {
#             $totalUsedQuota = QueueLocation::where('user_id', $user->id)
#                 ->where('id', '!=', $location->id)->sum('daily_quota');
#             if ($totalUsedQuota + $request->daily_quota > $user->queue_ticket) {
#                 return back()->with('error', 'Melebihi total antrian. Total antrian saat ini sisa: ' . max(0, $user->queue_ticket - $totalUsedQuota));
#             }
#         }
# Replace with empty string

content = re.sub(
    r"(\s*if \(\$request->daily_quota && \$user->queue_ticket !== null\) \{\s*\$totalUsedQuota = QueueLocation::where\('user_id', \$user->id\)\s*->where\('id', '!=', \$location->id\)->sum\('daily_quota'\);\s*if \(\$totalUsedQuota \+ \$request->daily_quota > \$user->queue_ticket\) \{\s*return back\(\)->with\('error', 'Melebihi total antrian\. Total antrian saat ini sisa: ' \. max\(0, \$user->queue_ticket - \$totalUsedQuota\)\);\s*\}\s*\})",
    "",
    content
)

with open('app/Http/Controllers/QueueManagementController.php', 'w') as f:
    f.write(content)
print("SUCCESS")
