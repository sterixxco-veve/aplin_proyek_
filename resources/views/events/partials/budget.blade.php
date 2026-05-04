<div class="d-flex justify-content-between mb-4">
    <h5 class="fw-bold">Budget</h5>

    <a href="/events/{{ $event->id_event }}/expenses"
       class="btn btn-primary">
        Go to Finance Page
    </a>
</div>

<div class="row g-3">

    <div class="col-md-4">
        <div class="p-3 bg-light rounded-3">
            <small>Total Budget</small>
            <h4>$12,500</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="p-3 bg-success-subtle rounded-3">
            <small>Spent</small>
            <h4>$8,200</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="p-3 bg-warning-subtle rounded-3">
            <small>Remaining</small>
            <h4>$4,300</h4>
        </div>
    </div>

</div>