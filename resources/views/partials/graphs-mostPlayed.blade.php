<h3>Nombre de parties par jeux joués (Jeux les plus joués)</h3>
<div style="width: calc(100% - 20px);">
    <button class="btn btn-primary graph-previous">Jeux précédents</button>
    <button class="btn btn-primary graph-next">Jeux suivants</button>

    <canvas class="graph-handler" id="mostPlayed"></canvas>

    <input type="hidden" class="type" value="bar">
    <input type="hidden" class="page" value="1">
    <input type="hidden" class="href" value="{{ url('ajaxMostPlayedPrevious/' . $GLOBALS['parameters']['general']['username']) }}">
    <input type="hidden" class="href-url" value="{{ url('ajaxMostPlayedGetUrl/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.chartData['mostPlayed'] = {
            labels: '',
            datasets: [
                {
                    label: "Parties jouées",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: ''
                }
            ]
        };
        window.chartOptions['mostPlayed'] = {
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
        var mostPlayedCtx = document.getElementById("mostPlayed").getContext("2d");
        window.chartInstance['mostPlayed'] = new Chart(mostPlayedCtx).Bar(window.chartData['mostPlayed'], window.chartOptions['mostPlayed']);
    </script>
</div>