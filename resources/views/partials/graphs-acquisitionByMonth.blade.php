<h3>Nombre de jeux et extensions acquis par mois</h3>
<div style="width: calc(100% - 20px);">
    <button class="btn btn-primary graph-previous">Mois précédents</button>
    <button class="btn btn-primary graph-next">Mois suivants</button>

    <canvas class="graph-handler" id="acquisitionByMonth" width="400" height="100"></canvas>

    <input type="hidden" class="type" value="bar">
    <input type="hidden" class="width" value="400">
    <input type="hidden" class="height" value="100">
    <input type="hidden" class="page" value="1">
    <input type="hidden" class="href" value="{{ url('ajaxAcquisitionPrevious/' . $GLOBALS['parameters']['general']['username']) }}">
    <input type="hidden" class="href-url" value="{{ url('ajaxAcquisitionByMonthGetUrl/' . $GLOBALS['parameters']['general']['username']) }}">

    <script>
        window.chartData['acquisitionByMonth'] = {
            labels: '',
            datasets: [
                {
                    label: "Nombre de jeux acquis",
                    fillColor: "#B7C3D3",
                    strokeColor: "#A0AAB7",
                    data: ''
                }
            ]
        };
        window.chartOptions['acquisitionByMonth'] = {
            barValueSpacing : 10,
            showTooltips: false,
            onAnimationComplete: function () {
                var ctx = this.chart.ctx;
                ctx.font = this.scale.font;
                ctx.fillStyle = this.scale.textColor;
                ctx.textAlign = "center";
                ctx.textBaseline = "bottom";

                this.datasets.forEach(function (dataset) {
                    dataset.bars.forEach(function (bar) {
                        ctx.fillText(bar.value, bar.x, bar.y);
                    });
                })
            }
        };
        var acquisitionByMonthCtx = document.getElementById("acquisitionByMonth").getContext("2d");
        window.chartInstance['acquisitionByMonth'] = new Chart(acquisitionByMonthCtx).Bar(window.chartData['acquisitionByMonth'], window.chartOptions['acquisitionByMonth']);
    </script>
</div>