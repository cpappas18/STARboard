window.onload = function()
{
    showContent();
    displayRatingStats();
}

/**
 * Tells user how many ratings hav been submitted through the app
 */
function displayRatingStats()
{
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "ratingStats.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send();
        let response = req.responseText;
        document.getElementById("ratingStats").innerHTML = response;
        
    }catch (exception)
    {
        alert("Request failed.");
    }
}

function parseCookies(cookies) {
    return cookies.split(';')
        .map(cookie => cookie.trim().split('='))
        .reduce((acc, cur) => {
                acc[cur[0]] = cur[1];
                return acc;
            }, {});
}

/**
 * Shows the dashboard
 */
function showContent()
{
    let cookies = parseCookies(document.cookie);
    let ticket = cookies['ticket'];
    try{
        const request = new XMLHttpRequest();
        request.open("POST", "dashboard.php", false);
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(`ticket=${ticket}`);
        let response = request.responseText;
        document.getElementById("personalizedDashboard").innerHTML = response;
        
    }catch (exception)
    {
        alert("Request failed.");
    }
}


