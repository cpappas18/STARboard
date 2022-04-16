/**
 * Returns registered courses from form input
 */
 function getRegisteredCourses() {
    let courses = document.getElementById("courses-list");
    let reg_courses = [];
    let course;

    for (let c=0; c<courses.children.length; c++) {
        course = courses.children[c].children[0]; // access nested input element
        if (course.value.length > 0) {
            reg_courses.push(course.value);
        }
    }

    return reg_courses;
}

/**
 * Returns account types from form input
 */
function getAccountTypes() {
    let checked = document.querySelectorAll('input[name=acct-type]:checked');
    let account_types = [];
    checked.forEach(type => account_types.push(type.id));
    return account_types;
}

/**
 * Clear all error messages
 */
function clearErrorMessages() {
    const error_div = document.getElementById("error-msg-cont");

    while (error_div.firstChild) {
        error_div.removeChild(error_div.lastChild);
    }
}

function confirmFieldsCompleted() {
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let first_name = document.getElementById("first-name").value;
    let last_name = document.getElementById("last-name").value;
    let email = document.getElementById("email").value;
    let account_types = getAccountTypes();
    let student_id = document.getElementById("student-id").value;
    let reg_courses = getRegisteredCourses();

    if (account_types.includes("student") || account_types.includes("TA")) {
        // students and TAs must have a student ID
        if (student_id.length == 0) {
            return false;
        }

        // students must also have registered courses
        if (account_types.includes("student")) {
            if (reg_courses.length == 0) {
                return false;
            }
        }
    }

    // all other fields must be filled out
    if (username.length > 0 && password.length > 0 && first_name.length > 0 
        && last_name.length > 0 && email.length > 0 && account_types.length > 0) {
            return true;
    } else {
        return false;
    }
        
}

function getAccount(username) {
    try {
        const req = new XMLHttpRequest();
        req.open("GET", `get_users.php?username=${username}`, true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                populateAccountInfo(req);
            }
        }
        req.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }    
}

function save() {
    let split_url = document.URL.split("=");

    if (split_url.length > 1) { // edit user account
        saveEdits();
    }
    else { // create new account
        saveNewAccount();
    }

}

function saveEdits() {
    clearErrorMessages();
    let complete = confirmFieldsCompleted();

    if (!complete) {
        let error_div = document.getElementById("error-msg-cont");
        error_div.innerHTML = "<p style='color:red'>All fields are required.</p>";
        return;
    }

    const old_username = document.URL.split("=")[1];
    const new_username = document.getElementById("username").value;
    const first_name = document.getElementById("first-name").value;
    const last_name = document.getElementById("last-name").value;
    const email = document.getElementById("email").value;
    const student_id = document.getElementById("student-id").value;

    // get account types from form
    let checked = document.querySelectorAll('input[name=acct-type]:checked');
    let account_types = [];
    checked.forEach(type => account_types.push(type.id));

    // get registered courses from form
    let courses = document.getElementById("courses-list");
    let reg_courses = [];
    let course;
    for (let c=0; c<courses.children.length; c++) {
        course = courses.children[c].children[0]; // access nested input element
        if (course.value.length > 0) {
            reg_courses.push(course.value);
        }
    }

    try {
        const req = new XMLHttpRequest();
        req.open("POST", "edit_user.php", true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(req.responseText, "text/xml");
                let error_msgs = xmlDoc.getElementsByClassName("error");
                
                // check if we received an error while trying to register
                if (error_msgs.length > 0) {
                    let error_div = document.getElementById("error-msg-cont");
    
                    // append all error messages
                    for (msg of error_msgs) {
                        error_div.appendChild(msg);
                    }
                } 
                else { // registration success
                    document.getElementById("account-info").innerHTML = req.responseText;
                }
            }
        }
        req.send(`old_username=${old_username}&new_username=${new_username}&firstname=${first_name}
        &lastname=${last_name}&email=${email}&accounttypes=${JSON.stringify(account_types)}
        &studentid=${student_id}&courses=${JSON.stringify(reg_courses)}`);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }    
}

function populateAccountInfo(request) {
    let parser = new DOMParser();
    let xmlDoc = parser.parseFromString(request.responseText, "text/xml");
    let data = xmlDoc.getElementsByTagName("td");

    let username = document.getElementById("username");
    let first_name = document.getElementById("first-name");
    let last_name = document.getElementById("last-name");
    let email = document.getElementById("email");
    let student_id = document.getElementById("student-id");

    // populate fields with AJAX response text
    username.value = data[0].innerHTML;
    first_name.value = data[1].innerHTML;
    last_name.value = data[2].innerHTML;
    email.value = data[3].innerHTML;
    student_id.value = data[5].innerHTML;

    // check the boxes with the account types
    let account_types = data[4].innerHTML.split(",");
    account_types.forEach(type => {
        type = type.trim(); // remove white space 
        type = convertAccountType(type); // convert to id names in html form 
        let checkbox = document.getElementById(type);
        checkbox.checked = true;
        toggleExtraInfo();
    });

    // fill in the registered courses
    let courses = data[6].innerHTML.split(",");
    let c = 1; // course index
    courses.forEach(course => {
        course = course.trim(); // remove white space
        let course_field = document.getElementById(`course-${c}`);
        course_field.value = course;
        c++;
    })
}

function convertAccountType(type) {
    switch (type) {
        case "Student": return "student";
        case "Professor": return "professor";
        case "TA Administrator": return "admin";
        case "TA": return "TA";
        case "System Operator": return "sys-operator";
    }
}

function toggleExtraInfo() {
    let courses = document.getElementById("courses-div");
    let id = document.getElementById("student-id-div");
    let student = document.getElementById("student");
    let TA = document.getElementById("TA");

    if ((student.checked && TA.checked) || student.checked) {
        courses.style.display = "block";
        id.style.display = "block";
    } else if (TA.checked) {
        courses.style.display = "none";
        id.style.display = "block";
    } else {
        courses.style.display = "none";
        id.style.display = "none";
    }
}

function saveNewAccount() {
    clearErrorMessages();
    let complete = confirmFieldsCompleted();

    if (!complete) {
        let error_div = document.getElementById("error-msg-cont");
        error_div.innerHTML = "<p style='color:red'>All fields are required.</p>";
        return;
    }

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let first_name = document.getElementById("first-name").value;
    let last_name = document.getElementById("last-name").value;
    let email = document.getElementById("email").value;
    let student_id = document.getElementById("student-id").value;
    let account_types = getAccountTypes(); // get account types from form input
    let reg_courses = getRegisteredCourses(); // get registered courses from form input

    try {
        const syncRequest = new XMLHttpRequest();
        syncRequest.open("POST", "../login/register.php", false);
        syncRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        syncRequest.send(`sender=sysop&username=${username}&password=${password}&firstname=${first_name}
        &lastname=${last_name}&email=${email}&accounttypes=${JSON.stringify(account_types)}
        &studentid=${student_id}&courses=${JSON.stringify(reg_courses)}`);

        if (syncRequest.status === 200) {
            let parser = new DOMParser();
            let xmlDoc = parser.parseFromString(syncRequest.responseText, "text/xml");
            let error_msgs = xmlDoc.getElementsByClassName("error");
            
            // check if we received an error while trying to register
            if (error_msgs.length > 0) {
                let error_div = document.getElementById("error-msg-cont");

                // append all error messages
                for (msg of error_msgs) {
                    error_div.appendChild(msg);
                }
            } 
            else { // registration success
                document.getElementById("account-info").innerHTML = syncRequest.responseText;
            }
        }
    } catch (exception) {
        console.log(exception);
        alert("Request failed. Please try again.");
    }
}

// on load
let split_url = document.URL.split("=");
let title = document.getElementById("title");

if (split_url.length > 1) { // edit user account
    let username = split_url[1];
    title.innerHTML = "Edit User Account";
    getAccount(username);
} else { // add new account
    let password_div = document.getElementById("password-div");
    password_div.style.display = "block";
    title.innerHTML =  "Create User Account";
}


