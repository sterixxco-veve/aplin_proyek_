class FinanceService
{
    public function approve($id, $approverId)
    {
        $report = FinanceReport::findOrFail($id);

        if ($report->status !== 'pending') {
            throw new \Exception('Already processed');
        }

        $report->status = 'approved';
        $report->save();

        return $report;
    }

    public function reject($id)
    {
        $report = FinanceReport::findOrFail($id);
        $report->status = 'rejected';
        $report->save();

        return $report;
    }
}