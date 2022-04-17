var CURRENT_COURSE=""; //The current course being displayed
var CURRENT_USER=""; //The name of the user currently logged in
var SELECTED_TA = "none"; //The TA selected. If the user is a TA, this is their name. Otherwise, this is the TA which the prof selects
var CURRENT_TERM = "Winter 2022"; //Should be updated when term changes
PERMISSION = ""; 

window.addEventListener('resize', responsiveMessageChannel);
/**
 * Opens the message channel
 */
function openMessages() 
{
    
    document.getElementById("messageChannel").style.height = "80%";
    document.getElementById("messageChannel").style.width = "45%";
    document.getElementById("messageChannel").style.padding = "20px";
    document.getElementById("main").style.marginLeft = "50%";
    document.getElementById("main").style.width = "50%";
    document.getElementById("displayMessagesButton").style.display="none";
    
    
}

/**
 * Closes the message channel
 */
function closeMessages() 
{
    document.getElementById("messageChannel").style.width = "0%";
    document.getElementById("messageChannel").style.height = "0%";
    document.getElementById("messageChannel").style.padding = "0px";
    document.getElementById("main").style.marginLeft= "0%";
    document.getElementById("main").style.width = "100%";
}

/**
 * Gets the user's TA selection from the "TA selected" dropdown
 * and display's the information (office hourse and responsibilites, performance log)
 * for the selected TA
 */
function selectTA()
{
    if (PERMISSION != "TA")
    {
        var selection = document.getElementById("TAselectionDropDown").value;
        document.getElementById("selectedTA").innerHTML = selection;
        SELECTED_TA = selection;
        toggleTASpecificItems(SELECTED_TA);
        
    }
    
    //Display performance and office hourse/responsibilities info
    getTAPreform();
    displayTAInfo();
} 

/**
 * Closes the "edit OH and responsibilities" dropdown and displays a 
 * success message
 */
function update_TA_OH_Responsibilities()
{
    toggleEditTAInfo();//toggle showing the edit view
    showSuccessMessage(); //display a  success message
}

/**
 * Displays a small pop-up message saying "records updated successfully"
 * which disapears after 3 seconds
 */
function showSuccessMessage() 
{
    // Get the div for the success message
    var x = document.getElementById("successMessage");
    // Add the "show" class
    x.className = "show";
    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

/**
 * Reads the messages from the database and displays the 
 * messages in the message channel section. Resets the scroll bar to the bottom
 * of the message channel.
 * 
 * This function is called every 2 seconds so that messages are constantly updated.
 */
function updateMessages() 
{
    try{
        let course = CURRENT_COURSE;
        let username = CURRENT_USER
        const req = new XMLHttpRequest();
        req.open("POST", "channelMessages.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`username=${username}&course=${course}`);
        document.getElementById("msgHist").innerHTML = req.responseText;
        
       
        
    }catch (exception)
    {
        alert("Messages request failed.");
    }
    //move scrollbar to bottom:
    if (document.getElementById("msgHist"))
    {
        document.getElementById("msgHist").scrollTop = document.getElementById("msgHist").scrollHeight;
    }
    

    //update messaages every 2 seconds:
    setTimeout(updateMessages, 2000);
}

/**
 * Displays the office hours and responsobilities for 
 * the current TA. 
 */
function displayTAInfo()
{
    let username = SELECTED_TA;
    let course = CURRENT_COURSE; 
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "displayTAInfo.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`username=${username}&course=${course}`);
        let response = req.responseText;
        document.getElementById("taInfo").innerHTML = response;
        
    }catch (exception)
    {
        alert("Request failed to display TA OH and responsibilities.");
    }
}

/**
 * When a user edits TA OH and responsibilities, this function is called to
 * update their changes in the database and display the new changes.
 * 
 * The TA OH and responsibilities section is updated. The TA "all TA report" also gets updated. A success message is shown.
 * 
 */
function updateTAInfo()
{
    let username = SELECTED_TA;
    let course = CURRENT_COURSE;
    let ohDay = document.getElementById("weekday").value;
    let ohStart = document.getElementById("startTime").value;
    let ohEnd = document.getElementById("endTime").value;
    let ohLocation = document.getElementById("location").value;
    let responsibilities = document.getElementById("responsibilities").value;
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "updateTAInfo.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`username=${username}&course=${course}&ohDay=${ohDay}&ohStart=${ohStart}&ohEnd=${ohEnd}
                &ohLocation=${ohLocation}&responsibilities=${responsibilities}`);
        
    }catch (exception)
    {
        alert("Update TA info request failed.");
    }
    displayTAInfo();
    toggleEditTAInfo();
    displayTAReport();
    showSuccessMessage(); 
}



/**
 * When the screen switches between PC and phone/tablet modes, this function changes the 
 * way the message channel is displayed. 
 */
function responsiveMessageChannel() 
{
    if (document.getElementById("responsiveMsgChannel"))
    {
        if (!window.matchMedia("(min-width: 950px)").matches) {
            //phone/tablet mode: display message channel as a full-screen div
            document.getElementById("responsiveMsgChannel").innerHTML = document.getElementById("messageChannelContents").innerHTML;
            document.getElementById("responsiveMsgChannel").style.display = "block";
            document.getElementById("messageChannel").style.display = "none";
            document.getElementById("openMessagesButton").style.display = "none";
            document.getElementById("closeMessagesButton").style.display = "none";
            closeMessages();

        } else { //PC mode: display message channel as a pup-up side bar
            document.getElementById("responsiveMsgChannel").innerHTML = "";
            document.getElementById("responsiveMsgChannel").style.display = "none";
            document.getElementById("messageChannel").style.display = "block";
            document.getElementById("openMessagesButton").style.display = "block";
            document.getElementById("closeMessagesButton").style.display = "block";
            closeMessages();
            
        }
    }
        
    
}




/**
 * Displays the performance log for the current TA
 */
 function getTAPreform()
 {
    if (PERMISSION != "TA")
    {
        try
        {
            let course = CURRENT_COURSE;
            let TA_name = SELECTED_TA;
            let prof_name = CURRENT_USER;
            const req = new XMLHttpRequest();
            req.open("POST", "taPerform.php", false);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(`TA_name=${TA_name}&course=${course}&prof_name=${prof_name}`);
            let response = req.responseText;
            document.getElementById("taPerformLogs").innerHTML = response;  
        }catch (exception)
        {
            alert("TA perfromance request failed.");
        }
    }
    
 }

/**
 * Called when a prof adds a TA performance log comment about their TA. 
 * Adds the comment to the database, displays the comment, and shows a success message.
 */
function updateTAPerform()
{
    if (PERMISSION != "TA")
    {
        let TA_name = SELECTED_TA;
        let course_num = CURRENT_COURSE;
        let prof_name = CURRENT_USER;
        let term_month_year = CURRENT_TERM +" "+ new Date().toLocaleDateString();
        let comment = document.getElementById("taPerform").value;
        if (comment.length > 0)
        {
            try{
                const req = new XMLHttpRequest();
                req.open("POST", "updateTAPerform.php", false);
                req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                req.send(`TA_name=${TA_name}&course_num=${course_num}&prof_name=${prof_name}&term_month_year=${term_month_year}&comment=${comment}`);
            
            }catch (exception)
            {
                alert("Update TA performance request failed.");
            }
            getTAPreform();
            //clear the input text box
            document.getElementById("taPerform").value = "";
            //update the all TAs report
            displayTAReport();
            showSuccessMessage() 
        }
    }
    

}

/**
 * Displays the prof's wishlist
 */
function displayWishList()
{
    if (PERMISSION != "TA")
    {
        let prof_name = CURRENT_USER;
        let course_num = CURRENT_COURSE; 
        try
        {
            const req = new XMLHttpRequest();
            req.open("POST", "displayWishList.php", false);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(`prof_name=${prof_name}&course_num=${course_num}`);
            let response = req.responseText;
            document.getElementById("wishList").innerHTML = response;
            
        }catch (exception)
        {
            alert("Display wishlist request failed.");
        }
    }
    
}

/**
 * Adds a new TA name to the prof's wish list
 */
function updateTAWishList()
{
    let TA_name = document.getElementById("addToWishList").value;;
    let term_month_year = CURRENT_TERM + new Date().toLocaleDateString();
    let course_num = CURRENT_COURSE;
    let prof_name = CURRENT_USER;
    if (PERMISSION != "TA" && TA_name.length > 0)
    {
        try{
        const req = new XMLHttpRequest();
        req.open("POST", "updateTAWishList.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`TA_name=${TA_name}&course_num=${course_num}&prof_name=${prof_name}&term_month_year=${term_month_year}`);
        }catch (exception)
        {
            alert("Update wishlist request failed.");
        }
        displayWishList();
        //clear the input text box
        document.getElementById("addToWishList").value = "";
        showSuccessMessage() 
    }
}

/**s associated with the current course and displays them in a the "select TA" drop down menu
 */
function getTAs()
{   
    if (PERMISSION != "TA")
    {
        let course = CURRENT_COURSE;
        try{
            const req = new XMLHttpRequest();
            req.open("POST", "selectTA.php", false);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(`course=${course}`);
            document.getElementById("TAselectionDropDown").innerHTML = req.responseText;
        
        }catch (exception)
        {
            alert("Get TAs request failed.");
        }
    }
    
}

/**
 * Displays the "all TA report" for the current course
 */
function displayTAReport()
{
    if (PERMISSION != "TA")
    {
        let prof_name = CURRENT_USER;
        let course_num = CURRENT_COURSE; 
        try{
            const req = new XMLHttpRequest();
            req.open("POST", "allTAReport.php", false);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(`prof_name=${prof_name}&course_num=${course_num}`);
            let response = req.responseText;
            document.getElementById("allTAReport").innerHTML = response;
            
        }catch (exception)
        {
            alert("Display TA report request failed.");
        }
    }
    
}

/**
 * Called when the user sends a message. Adds message to the database and displays 
 * the updated message channel
 */
function messageSent()
{
    let message = document.getElementById("msg").value;
    if (message.length > 0)
    {
        let username = CURRENT_USER;
        var today = new Date();
        var date = today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate();
        var time = today.getHours() + ":" + today.getMinutes();
        let dateAndTime = time+' on '+date;
        let course = CURRENT_COURSE;
        try{
            const req = new XMLHttpRequest();
            req.open("POST", "messageSent.php", false);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(`username=${username}&course=${course}&dateAndTime=${dateAndTime}&message=${message}`);
           
            //"Woosh noise"
            playMessageSentSound();
        }catch (exception)
        {
            alert("Message sent request failed.");
        }
        //clear the input and re-display the messages
        document.getElementById("msg").value = "";
        updateMessages();
    }
}

//Plays a "woosh" noise
function playMessageSentSound()
{
    let src = 'messageSentNoise.mp3';
    let audio = new Audio(src);
    audio.play();
}


//Pressing "enter" will also send a message:






 
/**
 * Open drop down to let user edit ta responsibilities and OH
 **/
function toggleEditTAInfo()
{
    let edit = document.getElementById("editInfo");
    if (edit)
    {
        if (edit.style.display == "none")
        {
            edit.style.display = "block";
            
        }
        else
        {
            edit.style.display = "none";
        }
    }
    
}

/**
 * Shows or hides TA specific info based on whether taName is selected
 **/
 function toggleTASpecificItems(taName)
 {
     console.log(taName);
     let ohResp = document.getElementById("respons");
     let perf = document.getElementById("perf");
     if (taName == "none")
     {
        ohResp.style.display = "none";
        perf.style.display = "none";
     }
     else
     {
        ohResp.style.display = "block";
        perf.style.display = "block";
     }
 }

/**
 * Show or hide the All TAs report, and change the button to say "Show report" or "Hide report" accordingly
 */
function toggleAllTAReport()
{
    let report = document.getElementById("allTAReport");
    if (report)
    {
        //Show the report
        if (report.style.display == "none")
        {
            report.style.display = "block";
            //Change the button to say 'hide report /\ '
            document.getElementById("showOrHideReportButton").innerHTML = 
            '<i class="fa fa-bar-chart" aria-hidden="true" style="padding-right: 15px; color: #2b2d42"></i> Hide report <i class="fa fa-angle-up" aria-hidden="true" style="color: #2b2d42"></i>';
        }
        //Hide the report
        else
        {
            report.style.display = "none";
            //Change the button to say 'show report \/ '
            document.getElementById("showOrHideReportButton").innerHTML = 
            '<i class="fa fa-bar-chart" aria-hidden="true" style="padding-right: 15px; color: #2b2d42"></i> Show report <i class="fa fa-angle-down" aria-hidden="true" style="color: #2b2d42"></i>';
        }
    }
}


/**
 * Parses the cokies
 * @param cookies 
 * @returns the cookies parsed
 */
function parseCookies(cookies) {
    return cookies.split(';')
        .map(cookie => cookie.trim().split('='))
        .reduce((acc, cur) => {
                acc[cur[0]] = cur[1];
                return acc;
            }, {});
}

/**
 * Gets the user associated with ticket
 * @param  ticket 
 * @returns the user name in the form firstname lastname
 */
function getUserFromTicket(ticket)
{
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "getUsername.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`ticket=${ticket}`);
        let response = req.responseText;
        return response;
        
    }catch (exception)
    {
        alert("Ticket request failed.");
    }
}

/**
 * 
 * @returns Gets the current info about the course being show, including the course, 
 * user name, and their permissions
 */
function getCurrentInfo()
{
    //get the current course from the url parameter
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const course = urlParams.get('Course')
    
    //get the ticket from cookie to update the current_user
    let cookies = parseCookies(document.cookie);
    let ticket = cookies['ticket'];
  
    //Use the ticket to get the current user 
    CURRENT_USER = getUserFromTicket(ticket);
    CURRENT_COURSE = course;
    let perm = getPermissions(ticket);
    PERMISSION = perm.trim();
    if (PERMISSION == "") { 
        // send the user back to the login page (kick them out) if they don't have permission
        window.location.replace('../dashboard/dashboard.html'); 
        return;
    }

    //if the user has TA permission, then selected_TA must be their own name
    
    if (perm.trim() == "TA")
    {
        SELECTED_TA = CURRENT_USER;
    }
    //display the main contents of the webpage
    displayBasedOnPermissions(ticket);
    

}

function getPermissions(ticket)
{
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "permissions.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`ticket=${ticket}&user=${CURRENT_USER}&course=${CURRENT_COURSE}`);
        let response = req.responseText;
        return response;
        
    }catch (exception)
    {
        alert("Permissions Request failed.");
    }
}


function displayBasedOnPermissions(ticket)
{
    
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "displayBasedOnPermissions.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(`ticket=${ticket}&user=${CURRENT_USER}&course=${CURRENT_COURSE}`);
        let response = req.responseText;
        document.getElementById("contentSection").innerHTML = response;
        
    }catch (exception)
    {
        alert("Display Request failed.");
    }

}
function displayTitle()
{
    document.getElementById("courseTitle").innerHTML = CURRENT_COURSE;
}


function initialize()
{
    getCurrentInfo();
    if (PERMISSION != "")
    {
        updateMessages();
        getTAs();
        displayWishList();
        displayTitle()
        responsiveMessageChannel();
        displayTAReport();
        displayTAInfo();
        selectTA();
    
    }
    
    
}
window.onload = initialize();
    
document.getElementById("msg").addEventListener("keyup", function(event)
{
    if (event.keyCode === 13) //enter key
    {
        event.preventDefault();
        messageSent();
    }
});







