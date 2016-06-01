<h3>Nombre de jeux par type</h3>
<div style="width: calc(100% - 20px);">
    <button id="most-type-previous-games" class="btn btn-primary">Types précédents</button>
    <button id="most-type-next-games" class="btn btn-primary">Types suivants</button>

    <canvas id="chart-most-type" width="400" height="200"></canvas>

    <input type="hidden" id="most-type-page" value="1">
    <input type="hidden" id="most-type-href" value="{{ url('ajaxMostTypePrevious/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.mostTypeChartData = {
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
        window.mostTypeOptions = {
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
        var mostTypeCtx = document.getElementById("chart-most-type").getContext("2d");
        var mostTypeChart = new Chart(mostTypeCtx).Bar(window.mostTypeChartData, window.mostTypeOptions);
    </script>
</div>