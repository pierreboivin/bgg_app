<h3>Nombre de parties par durée des jeux possédés</h3>
<div style="width: calc(100% - 20px);">
    <canvas id="playsByLength"></canvas>

    <div id="plays-by-length-legend" class="legend"></div>
    <script>
        window.chartOptions['playsByLength'] = {
            segmentShowStroke: false,
            animateRotate: true,
            animateScale: false,
            percentageInnerCutout: 50
        };
        var playByLengthCtx = document.getElementById("playsByLength").getContext("2d");
        window.chartInstance['playsByLength'] = new Chart(playByLengthCtx).Pie({!! $graphs['playsByLength']['serie'] !!}, window.chartOptions['playsByLength']);

        $('#plays-by-length-legend').append(window.chartInstance['playsByLength'].generateLegend());
    </script>
</div>