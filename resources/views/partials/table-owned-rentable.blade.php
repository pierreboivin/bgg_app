<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux les plus rentables</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats owned-rentable">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées totales</th><th>Valeur du jeu</th><th>Prix par partie</th></tr>
            </thead>
            <tbody>
                @include('partials.lines-table-owned-rentable')
            </tbody>
        </table>
        <button id="table-owned-rentable-previous" data-page="2" data-replace="table.owned-rentable tbody" data-href="{{ url('ajaxTableOwnedRentablePrevious/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary">Plus</button>
    </div>
</div>