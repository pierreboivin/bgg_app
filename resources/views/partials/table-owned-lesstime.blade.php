<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux possédés les plus récemment joués</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats less-time">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées totales</th><th>Date de la dernière partie</th></tr>
            </thead>
            <tbody>
                @include('partials.lines-table-owned-lesstime')
            </tbody>
        </table>
        <button data-page="2" data-replace="table.less-time tbody" data-href="{{ url('ajaxTableLessTimePrevious/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary table-more-button">Plus</button>
    </div>
</div>
