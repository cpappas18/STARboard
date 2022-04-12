
<html>
<head>
    <title>STARboard Courses</title>
    <!--- Style sheet: ---->
    <link href="websiteShell.css" rel="stylesheet">
    <link href="coursesPage.css" rel="stylesheet">

    <!--- Icons: ---->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/font-awesome.min.css">

    <!--- Fonts: ---->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat+Subrayada:wght@700&family=Playfair+Display:ital,wght@1,600&family=Raleway&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat+Subrayada:wght@700&family=Open+Sans&family=Playfair+Display:ital,wght@1,600&family=Raleway:wght@400;600&display=swap" rel="stylesheet">


</head>
<body>

    <div class = "topNav">

        <!--- TOPNAV: Logo and website name --->
    
        <img class = "topNavLogo" src="https://cdn.freebiesupply.com/logos/large/2x/mcgill-university-1-logo-png-transparent.png">
        <div class="logoText">
            STAR<span style = "color: white">board</span>
        </div>
    
    
        <!--- RATINGS BUTTON--->
        <a class="topNavOption" onclick="menuItemSelected('ratings')" id="ratings">
            <i class="fa fa-thumbs-up" aria-hidden="true"></i>RATINGS
        </a>
    
        <!--- COURSES BUTTON WITH DROP-DOWN--->
        <div class = "topNavOption" onclick="menuItemSelected('courses')" id="courses">
            <div class="dropDownMenu">
    
                <!--- COURSES BUTTON--->
                <a class="dropDownButton" onclick="toggleDropDown('courses_dropdown')">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    COURSES
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </a>
    
                <!--- DROP-DOWN--->
                <div class="dropDown_contents" style="display: none;" id="courses_dropdown">
                    <!--- TODO: Personalize te course list for each user -->
                    <a href="#"><i class="fa fa-bookmark" aria-hidden="true"></i> COMP 307</a>
                    <a href="#"><i class="fa fa-bookmark" aria-hidden="true"></i> COMP 424</a>
                    <a href="#"><i class="fa fa-bookmark" aria-hidden="true"></i> COMP 322</a>
                </div>
            </div>
        </div>
    
        <!--- ADMIN BUTTON--- (FOR ADMIN AND SYSOP ONLY)-->
        <div class="topNavOption" id="admin" onclick="menuItemSelected('admin')" id="admin">
            <i class="fa fa-sliders" aria-hidden="true"></i><a class = "buttonLabel" id="adminButton"> ADMIN</a>
        </div>
    
        <!--- SYSTEM BUTTON WITH DROP-DOWN--- (FOR SYSOP ONLY)-->
        <div class = "topNavOption" onclick="menuItemSelected('system')" id="system">
            <div class="dropDownMenu">
                <!--- SYSTEM BUTTON--->
                <a class="dropDownButton" onclick="toggleDropDown('system_dropdown')">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    SYSTEM
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </a>
                <!--- DROP-DOWN--->
                <div class="dropDown_contents" style="display: none;"  id="system_dropdown">
                    <a href="#"><i class="fa fa-users" aria-hidden="true"></i> Manage users</a>
                    <a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Import professor</a>
                    <a href="#"><i class="fa fa-upload" aria-hidden="true"></i> Import course</a>
                </div>
            </div>
        </div>
    
        <!--- SIGNOUT BUTTON WITH DROP-DOWN--->
        <div class = "topNavOption" onclick="menuItemSelected('signout')" id="signout">
            <div class="dropDownMenu">
                <!--- SIGN OUT BUTTON--->
                <a class="dropDownButton" onclick="toggleDropDown('signout_dropDown')">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    SIGN-OUT
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </a>
                <!--- DROP-DOWN--->
                <div class="dropDown_contents" style="display: none;"  id="signout_dropDown">
                    <a href="#">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        Confirm sign-out
                    </a>
                </div>
            </div>
        </div>
    
        <!--- HAMBURGER BUTTON to toggle showing menu options--- (PHONE MODE ONLY)-->
        <a  class="hamburger" onclick="showTopNavOptions()" >
            <i class="fa fa-bars" style = "color: white;"></i>
        </a>
    </div>
    
    <!--- MAIN CONTENT OF WEBPAGE: UNIQUE FOR EACH PAGE --->
    <div class = "contentSection">

    <div class = "messageChannel">
        <form name = "messageForm" method = "post" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <div class ="messageHistory" id="msgHist">
                ...
            </div>
            
            <input type="text" placeholder="Send a message..." id="msg" name ="msg">
            <input type="submit" name="Submit" id="Submit" value="Submit">
        </form>

    <?php
        try{
            //connect to database
            $db = new PDO('sqlite:channel.db');
            //generate sql statement and provide placeholders for values
            $sql = "INSERT INTO messages (username, dateAndTime, course, message) VALUES
            (:username, :dateAndTime, :course, :message)";
            $stmt = $db->prepare($sql);

            //get contents from form and put into database
            $username = "Maddy";
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);

            $dateAndTime = time();
            $stmt->bindValue(':dateAndTime', $dateAndTime, PDO::PARAM_STR);

            $course = "COMP307";
            $stmt->bindValue(':course', $course, PDO::PARAM_STR);

            $message = filter_input(INPUT_POST, 'msg');
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            
            //execute the statement
            $stmt->execute();
        }catch(PDOException $e)
        {
            print "there was an error" . e->getMessage();
            die();
        }

    ?>

    </div>
    <div class="courseInfo">
        </h2>Office hours and responsibilities</h2> ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.

    </div>
    <div class = "courseInfo">
    </h2>TA preformance log</h2> ab ilnam aliquam quaerat voluptatem.
    ae ab illo inventore veritatis et quasi arfugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.

    </div>
    <div class="courseInfo">
    </h2>TA Wish List</h2> ab illo inventore enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.

    </div>

    </div>
    
    
    
    <script>
     

     function updateMessages() {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById("msgHist").innerHTML =
            this.responseText;
        }
        xhttp.open("GET", "channelMessages.php");
        xhttp.send();
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
    
    </script>
    
    </body>
    </html>