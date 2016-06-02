<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux les moins rentables</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats less-rentable">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées totales</th><th>Valeur du jeu</th><th>Prix par partie</th></tr>
            </thead>
            <tbody>
            @include('partials.lines-table-rentable', ['type' => 'less'])
            </tbody>
        </table>
        <button data-page="2" data-replace="table.less-rentable tbody" data-href="{{ url('ajaxTableRentable/less/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary table-more-button">Plus</button>
    </div>
</div>