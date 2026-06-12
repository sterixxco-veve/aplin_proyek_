use Illuminate\Support\Facades\DB;

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database Connected';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});