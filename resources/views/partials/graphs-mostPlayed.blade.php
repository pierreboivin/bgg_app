<h3>Jeux les plus joués</h3>
<div style="width: calc(100% - 20px);">
    <button id="chart-most-played-previous-games" data-page="2" data-href="{{ url('ajaxMostPlayedPrevious/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary">Jeux précédents</button>
    <canvas id="chart-most-played" width="400" height="200"></canvas>
    <script>
        window.mostPlayedChartData = {
            labels: [{!! $graphs['mostPlayed']['labels'] !!}],
            datasets: [
                {
                    label: "Parties joués",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: [{!! $graphs['mostPlayed']['serie1'] !!}]
                }
            ]
        };
        window.mostPlayedOptions = {
            barValueSpacing : 10,
            showTooltips: false,
            onAnimationComplete: function () {
                var ctx = this.chart.ctx;
                ctx.font = this.scale.font;
                ctx.fillStyle = this.scale.textColor
                ctx.textAlign = "center";
                ctx.textBaseline = "bottom";

                this.datasets.forEach(function (dataset) {
                    dataset.bars.forEach(function (bar) {
                        ctx.fillText(bar.value, bar.x, bar.y);
                    });
                })
            }
        };
        var mostPlayedCtx = document.getElementById("chart-most-played").getContext("2d");
        var mostPlayedChart = new Chart(mostPlayedCtx).Bar(window.mostPlayedChartData, window.mostPlayedOptions);
    </script>
</div>