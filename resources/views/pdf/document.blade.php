<h1>{{ $doc->event->nama_event }}</h1>

@if($doc->tipe_dokumen == 'proposal')
    <h3>Proposal</h3>
    <p>{{ $doc->latar_belakang }}</p>
    <p>{{ $doc->deskripsi }}</p>
@endif

@if($doc->tipe_dokumen == 'lpj')
    <h3>LPJ</h3>
    <p>{{ $doc->realisasi_kegiatan }}</p>
    <p>{{ $doc->evaluasi }}</p>
@endif