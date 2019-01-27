<h3>Nombre de jeux par type</h3>
<div style="width: calc(100% - 20px);">

    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-primary graph-next">Types suivants</button>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary graph-previous">Types précédents</button>
        </div>
    </div>

    <canvas class="graph-handler" id="mostType"></canvas>

    <input type="hidden" class="type" value="bar">
    <input type="hidden" class="page" value="1">
    <input type="hidden" class="href" value="{{ url('ajaxMostTypePrevious/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.chartData['mostType'] = {
            labels: '',
            datasets: [
                {
                    label: "Nombre de jeux",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: ''
                }
            ]
        };
        window.chartOptions['mostType'] = {
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
        var mostTypeCtx = document.getElementById("mostType").getContext("2d");
        window.chartInstance['mostType'] = new Chart(mostTypeCtx).Bar(window.chartData['mostType'], window.chartOptions['mostType']);
    </script>
</div>