function handleStateChange(request) {          
    let parser = new DOMParser();
    let xmlDoc = parser.parseFromString(request.responseText, "text/xml");

    let scripts = xmlDoc.getElementsByTagName("script");
    if (scripts.length > 0) {
        document.body.innerHTML = request.responseText;
        scripts = document.body.getElementsByTagName("script");
        eval(scripts[0].text); // execute the declaration code for the returned function so that the browser knows it exists
        redirect(); // redirect to the login page if the ticket is invalid
    } 
    else {
        document.getElementById("topNav").innerHTML = request.responseText;
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

function getHeader() {
    let cookies = parseCookies(document.cookie);
    let ticket = cookies['ticket'];

    try {
        // get page content which depends on ticket permissions
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                handleStateChange(this);
            }
        }
        request.open("POST", "../dashboard/header.php", false);
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(`ticket=${ticket}`);
    } catch (exception) {
        alert("User verification failed. Please reload the page to try again.");
    }
}

/**
 * Makes the selected topNav option have a border and removes all other borders
 * @param id the id of the topnavOption div
 */
function menuItemSelected(id)
{
    let items = document.getElementsByClassName("topNavOption");
    for (let i=0; i<items.length; i++)
    {
        let item = items[i];
        item.style.border = "none";
    }
    document.getElementById(id).style.border = "2px solid #8d99ae";
}

/**
 * Closes the drop down menu unless the click is within the dropdown menu
 * @param element
 */
window.onclick = function(element) {
    if (!element.target.matches(".dropDownButton")) {
        let items = document.getElementsByClassName("dropDown_contents");
        for (let i=0; i<items.length; i++)
        {
            let item = items[i];
            item.style.display = "none";
        }
    }
}

/**
 * Opens or closes the dropdown menu associated with id.
 * @param id the id for a topNavOption
 */
function toggleDropDown(id)
{
    var d = document.getElementById(id);
    if (d.style.display === "none")
    {
        d.style.display = "block";
    }
    else
    {
        d.style.display = "none";
    }
}

function showTopNavOptions() {
    let options = document.getElementsByClassName("topNavOption");
    for (let i=0; i<options.length; i++)
    {
        let option = options[i];
        console.log(option.className);
        if (option.className === "topNavOption") 
        {
            option.className += " responsive";
        }   
        else 
        {
            option.className = "topNavOption";
        }
    }
}

getHeader();