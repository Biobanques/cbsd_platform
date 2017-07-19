$(document).ready(function ()
{
    var refreshId = setInterval(function ()
    {
        var r = (-0.5) + (Math.random() * (1000.99));
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        var n = d.getSeconds();
        if (h === 23 && m >= 30 && m <= 59) {
            t = 60 - m;
            document.getElementById('img-container').innerHTML = '<div class="alert in alert-block fade alert-info">INFORMATION : Une maintenance aura lieu dans ' + t + ' min. La plateforme CBSD sera indisponible.<br>INFORMATION : The maintenance will take place in ' + t + ' min. The CBSD platform will be unavailable.</div>';
        }
    }, 1000);
});