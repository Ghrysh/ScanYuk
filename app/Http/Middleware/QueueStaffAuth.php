<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QueueStaffAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('queue_staff_id')) {
            return redirect()->route('queue.staff.login');
        }
        return $next($request);
    }
}
