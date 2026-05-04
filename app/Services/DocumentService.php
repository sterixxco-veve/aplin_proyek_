use Barryvdh\DomPDF\Facade\Pdf;

class DocumentService
{
    public function generate($docId)
    {
        $doc = EventDocument::with('event')->findOrFail($docId);

        $pdf = Pdf::loadView('pdf.document', [
            'doc' => $doc
        ]);

        $path = 'documents/doc_'.$docId.'.pdf';
        Storage::put($path, $pdf->output());

        return $path;
    }
}