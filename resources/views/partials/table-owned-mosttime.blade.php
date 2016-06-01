<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux possédés les plus anciennement joués</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats most-time">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées totales</th><th>Date de la dernière partie</th></tr>
            </thead>
            <tbody>
                @include('partials.lines-table-owned-mosttime')
            </tbody>
        </table>
        <button data-page="2" data-replace="table.most-time tbody" data-href="{{ url('ajaxTableMostTimePrevious/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary table-more-button">Plus</button>
    </div>
</div>