<script src="<?= asset_url().'assets/chartjs/Chart.bundle.min.js'; ?>"></script>
<div style="width :75%">

    <?php
    $dates = array();
    foreach($info as $site){
        foreach ( $site as $date ){
            array_push($dates, $date['date']);
        }
    }

    array_unique($dates);

    usort($dates, function($a1, $a2) {
        $v1 = strtotime($a1);
        $v2 = strtotime($a2);
        return $v1 - $v2; // $v2 - $v1 to reverse direction
    });

    ?><canvas id="graphique"></canvas></div>
<script>
    var ctx = document.getElementById('graphique');
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    var datas = {
        datasets: [<?php $i =0; foreach($info as $site => $value){ ?>

            {
                label: "<?= $site;?>",
                fill : false,
                borderColor : sColor = getRandomColor(),
                backgroundColor : sColor,
                data: [
                    <?php $j = 0; foreach ($value as $date){
                    echo "{ x :'".$date['date']."', y:".$date['price']."}";
                    if ( $j+1 != sizeof($value)){ ?>

                    ,
                    <?php }
                    $j++;
                    }?>
                ]
            }
            <?php if ( $i+1 != sizeof($info)){ ?>
            ,
            <?php }
            $i++;
            }?>
        ],
        labels: [
            <?php
                $last = end($dates);
            for($i = 0; $i < sizeof($dates); $i++){
                echo "'".$dates[$i]."'";
                if ( $last != $dates[$i]){
                    echo ",";
                }
            }
            ?>]
    };

    var mixedChart = new Chart.Line(ctx, {
        data: datas,
        options: {
            responsive : true,
            hoverMode: 'index',
            stacked : false,
            scales: {
                yAxes: [{
                    type : 'linear',
                    display : true,
                    position : 'left'
                }]
            }
        }
    });
</script>