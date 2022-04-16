<?php //returns whether $user can view $course as a ADMIN_SYSOP, TA, PROF, or they don't have permission
   
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    require_once("../dashboard/tickets.php");
    $course = $_POST['course'];
    $ticket = $_POST['ticket'];
    $user = $_POST['user'];
    $permissions = get_ticket_permissions($ticket);

    //Check if the user is a TA for course
    if (in_array("TA", $permissions))
    {
        $TA_name = $user;
        $query = $db->prepare('SELECT * FROM "TAmanagement" WHERE "taName" = :TA_name');
        $query->bindValue(':TA_name', $TA_name);
        $exec = $query->execute();
        while ($row = $exec->fetchArray(SQLITE3_ASSOC)) {
            if ($row['course'] == $course)
            {
                displayTAContent();
                break;
            }
        }
    }
    else if (in_array("professor", $permissions) || in_array("sys-operator", $permissions) 
            || in_array("admin", $permissions))
    {
        displayAllContent();
    }
    else{

    }

    function displayTAContent()
    {
        echo '<!--- MESSAGE CHANNEL --->
        <div class = "messageChannel" id="messageChannel">
            <div id="messageChannelContents">
                <span class="title">Message board</span>
    
                <!--- CLOSE MESSAGE CHANNEL --->
                <div class="closebtn" id="closeMessagesButton" onclick="closeMessages()"> 
                    <i class="fa fa-times" aria-hidden="true" style="color: rgba(43, 46, 66, 0.6)"></i>
                </div>
                <div class ="messageHistory" id="msgHist">
                    There was an issue loading your message history. 
                </div>
                <!---Message and send button-->
                <div class="inputAndButton">
                    <input class = "messageInput" type="text" placeholder="Send a message..." id="msg" name ="msg" autocomplete="off">
                    <button  class="sendButton" onclick="messageSent()">
                    <i class="fa fa-paper-plane" aria-hidden="true" style="color: #2b2d42" ></i>
                    </button>
                </div>
            </div>
        </div>
            
            
    <!--- OTHER TA MANAGEMENT INFO --->
    <div id="main">
    
        <!--- DISPLAY THE CURRENT COURSE --->
        <div >
            <span id="courseTitle" class = "courseTitle">TA managment</span>
        </div>
    
        <!--- BUTTON TO OPEN MESSAGE CHANNEL --->
        <div class = "displayMessagesButton" id="openMessagesButton" onclick="openMessages()">
        <i class="fa fa-comments-o" aria-hidden="true" style="color: white"></i>  
        </div>
        
        <!--- MESSAGE CHANNEL GETS MOVED INSIDE "MAIN" FOR PHONE/TABLET MODES--->
        <div class="responsiveMessageChannel" id="responsiveMsgChannel">
    
        </div>
    
        
        <!--- TA OH AND RESPOSIBILITIES (TAs and PROFS can see this) --->
        <div class = "courseInfo" id="respons">
            <div class="title">Office hours and responsibilities</div> 
           <!---Load info from database:--> 
                <div class="taInfo" id="taInfo">
                   
                </div>
            
            <!---Edit info:--> 
            <button class="editButton" onClick = "toggleEditTAInfo()" value="Edit" name="Edit">
                Edit info <i class="fa fa-pencil" aria-hidden="true" style="color: #2b2d42"></i>
            </button>
           <div class="editInfo" id="editInfo" style="display: none">
               <div class="editInfoInput">
                   <!---Edit OH DAY:--> 
                    <label for="weekday">Office hour day: </label>
                    <select id="weekday">
                        <option value="">Select a day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                    </select>
               </div>
               <!---Edit OH times:--> 
                <div class="editInfoInput">
                    <label for="startTime">Start time: </label>
                    <input type="time" id="startTime">
    
                    <label for="endTime">End time: </label>
                    <input type="time" id="endTime">
                </div>
                <!---Edit OH location:--> 
                <div class="editInfoInput">
                    <label for="location">Office hour location: </label>
                    <input type="text" id="location" placeholder="example: Trotier room 3103" class = "editInfoInput">
                </div>
                <!---Edit responsibilities:--> 
                <div class="editInfoInput">
                    <label for="responsibilities">TA responsibilities: </label>
                    <input type="text" id="responsibilities" placeholder="example: grading assignments" class = "editInfoInput">
                </div>
                
                <!---Updates the TA info to the new information:--> 
                <button class="smallEditButton" onclick="updateTAInfo()">
                    Save and close
                </button>
                <!---Closes the dropdown:--> 
                <button class="smallEditButton" onclick="toggleEditTAInfo()">
                    Discard changes
                </button>
            </div>
        </div>
    
    </div>
    
        <!---Little success message which pops up when changes are written to DB:-->
        <div id="successMessage">Records updated successfully.</div>';
    }

    function displayAllContent()
    {
        echo '<!--- MESSAGE CHANNEL --->
        <div class = "messageChannel" id="messageChannel">
            <div id="messageChannelContents">
                <span class="title">Message board</span>
    
                <!--- CLOSE MESSAGE CHANNEL --->
                <div class="closebtn" id="closeMessagesButton" onclick="closeMessages()"> 
                    <i class="fa fa-times" aria-hidden="true" style="color: rgba(43, 46, 66, 0.6)"></i>
                </div>
                <div class ="messageHistory" id="msgHist">
                    There was an issue loading your message history. 
                </div>
                <!---Message and send button-->
                <div class="inputAndButton">
                    <input class = "messageInput" type="text" placeholder="Send a message..." id="msg" name ="msg" autocomplete="off">
                    <button  class="sendButton"  onclick="messageSent()">
                    <i class="fa fa-paper-plane" aria-hidden="true" style="color: #2b2d42" ></i>
                    </button>
                </div>
            </div>
        </div>
            
            
    <!--- OTHER TA MANAGEMENT INFO --->
    <div id="main">
    
        <!--- DISPLAY THE CURRENT COURSE --->
        <div  >
            <span id="courseTitle" class = "courseTitle">TA managment</span>
        </div>
    
        <!--- BUTTON TO OPEN MESSAGE CHANNEL --->
        <div class = "displayMessagesButton" id="openMessagesButton" onclick="openMessages()">
        <i class="fa fa-comments-o" aria-hidden="true" style="color: white"></i>  
        </div>
        
        <!--- MESSAGE CHANNEL GETS MOVED INSIDE "MAIN" FOR PHONE/TABLET MODES--->
        <div class="responsiveMessageChannel" id="responsiveMsgChannel">
    
        </div>
    
        <!--- SELECT A TA TO MANAGE (TAs CANT VIEW THIS) --->
        <div class="courseInfo">
            <span class="title">TA selected:</span>
            <span class="TAselected" id="selectedTA">none</span> 
            
            <select class="chooseTA" onchange="selectTA()" id="TAselectionDropDown">
                <!--- use php to load TA names from DB-->
            </select>
        </div>
    
        <!--- TA OH AND RESPOSIBILITIES (TAs and PROFS can see this) --->
        <div class = "courseInfo" id="respons">
            <div class="title">Office hours and responsibilities</div> 
           <!---Load info from database:--> 
                <div class="taInfo" id="taInfo">
                   
                </div>
            
            <!---Edit info:--> 
            <button class="editButton" onClick = "toggleEditTAInfo()" value="Edit" name="Edit">
                Edit info <i class="fa fa-pencil" aria-hidden="true" style="color: #2b2d42"></i>
            </button>
           <div class="editInfo" id="editInfo" style="display: none">
               <div class="editInfoInput">
                   <!---Edit OH DAY:--> 
                    <label for="weekday">Office hour day: </label>
                    <select id="weekday">
                        <option value="">Select a day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                    </select>
               </div>
               <!---Edit OH times:--> 
                <div class="editInfoInput">
                    <label for="startTime">Start time: </label>
                    <input type="time" id="startTime">
    
                    <label for="endTime">End time: </label>
                    <input type="time" id="endTime">
                </div>
                <!---Edit OH location:--> 
                <div class="editInfoInput">
                    <label for="location">Office hour location: </label>
                    <input type="text" id="location" placeholder="example: Trotier room 3103" class = "editInfoInput">
                </div>
                <!---Edit responsibilities:--> 
                <div class="editInfoInput">
                    <label for="responsibilities">TA responsibilities: </label>
                    <input type="text" id="responsibilities" placeholder="example: grading assignments" class = "editInfoInput">
                </div>
                
                <!---Updates the TA info to the new information:--> 
                <button class="smallEditButton" onclick="updateTAInfo()">
                    Save and close
                </button>
                <!---Closes the dropdown:--> 
                <button class="smallEditButton" onclick="toggleEditTAInfo()">
                    Discard changes
                </button>
            </div>
        </div>
    
        <!---TA PERFORMANCE LOG (PROF ONLY):--> 
        <div class = "courseInfo" id="perf">
            <div class="title">TA performance log</div> 
            <!---DISPLAY PERFORMANCE LOG :--> 
            <div class="taPerformLogs" id="taPerformLogs">
                No performance logs to display.
            </div>
            
            <!---WRITE A TA PERFORMANCE LOG :--> 
            <input name="taPreformance" id="taPerform"  class="performanceInput" placeholder="Comment on performance...">
            <button class="editPerformButton" onclick="updateTAPerform()" >
                Save changes
                <i class="fa fa-check" aria-hidden="true" style="color: #2b2d42"></i>
            </button> 
        </div>
    
        <!---TA PERFORMANCE WISH LIST (PROF ONLY):--> 
        <div class="courseInfo">
            <div class="title">TA Wish List</div> 
            <div class="wishList" id="wishList">
                Your wish-list is currently empty.
            </div>
            <!---WRITE A TA PERFORMANCE LOG :--> 
            <input name="addToWishList" id="addToWishList"  class="performanceInput" placeholder="Add a TA to your wish-list...">
            <button class="editPerformButton" onclick="updateTAWishList()" >
                Save changes
                <i class="fa fa-check" aria-hidden="true" style="color: #2b2d42"></i>
            </button> 
    
        </div>
    
        <!---All TAs report-->
    
        <div class = "courseInfo" >
            <div class="title">
                All TAs report
            </div>
            <div class="editButton" id="showReportButton" onClick="toggleAllTAReport()" style="text-align: center;">
                
                <div id="showOrHideReportButton">
                    <i class="fa fa-bar-chart" aria-hidden="true" style="padding-right: 15px; color: #2b2d42"></i>
                    Show report 
                    <i class="fa fa-angle-down" aria-hidden="true" style="color: #2b2d42"></i>
                </div>
            </div>
            <div id="allTAReport" style="display: none;">
            </div>
            
    
            </div>
    
        </div>
    
    </div>
    
        <!---Little success message which pops up when changes are written to DB:-->
        <div id="successMessage">Records updated successfully.</div>';
    }



?>

    