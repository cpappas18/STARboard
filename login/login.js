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

function toggleLogin() {
    let login = document.getElementById("login-form");
    let reg = document.getElementById("reg-form");
    let cont = document.getElementById("reg-success-msg-cont");

    if (login.style.display === "none") {
        login.style.display = "block";
        reg.style.display = "none";
        cont.style.display = "none";
    } else {
        clearRegistrationFields();
        login.style.display = "none";
        reg.style.display = "block";
        cont.style.display = "none";
    }
}

function toggleSuccessMsgCont() {
    let reg = document.getElementById("reg-form");
    let cont = document.getElementById("reg-success-msg-cont");

    if (reg.style.display === "none") {
        reg.style.display = "block";
        cont.style.display = "none";
    } else {
        reg.style.display = "none";
        cont.style.display = "block";
    }
}

/**
 * Returns registered courses from form input
 */
function getRegisteredCourses() {
    let courses = document.getElementById("courses");
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

function clearRegistrationFields() {
    document.getElementById("username-reg").value = "";
    document.getElementById("password-reg").value = "";
    document.getElementById("first-name").value = "";
    document.getElementById("last-name").value = "";
    document.getElementById("email").value = "";
    document.getElementById("student-id").value = "";

    let checked = document.querySelectorAll('input[name=acct-type]:checked');
    checked.forEach(type => type.checked = false);

    let courses = document.getElementById("courses");
    let course;

    for (let c=0; c<courses.children.length; c++) {
        course = courses.children[c].children[0]; // access nested input element
        if (course.value.length > 0) {
            course.value = "";
        }
    }

    toggleExtraInfo(); // hide extra info sections
    clearErrorMessages();
}


function confirmFieldsCompleted() {
    let username = document.getElementById("username-reg").value;
    let password = document.getElementById("password-reg").value;
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

function sendAccountCreationRequest() {
    clearErrorMessages();
    let complete = confirmFieldsCompleted();

    if (!complete) {
        let error_div = document.getElementById("error-msg-cont");
        error_div.innerHTML = "<p style='color:red'>All fields are required.</p>";
        return;
    }

    let username = document.getElementById("username-reg").value;
    let password = document.getElementById("password-reg").value;
    let first_name = document.getElementById("first-name").value;
    let last_name = document.getElementById("last-name").value;
    let email = document.getElementById("email").value;
    let student_id = document.getElementById("student-id").value;
    let account_types = getAccountTypes(); // get account types from form input
    let reg_courses = getRegisteredCourses(); // get registered courses from form input

    try {
        const syncRequest = new XMLHttpRequest();
        syncRequest.open("POST", "register.php", false);
        syncRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        syncRequest.send(`sender=user&username=${username}&password=${password}&firstname=${first_name}
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
                document.getElementById("reg-success-msg-cont").innerHTML = syncRequest.responseText;
                toggleSuccessMsgCont();
            }
        }
    } catch (exception) {
        console.log(exception);
        alert("Request failed. Please try again.");
    }
}

function sendLoginRequest() {
    let username = document.getElementById("username-login").value;
    let password = document.getElementById("password-login").value;

    try {
        const syncRequest = new XMLHttpRequest();
        syncRequest.open("POST", "login.php", false);
        syncRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        syncRequest.send(`username=${username}&password=${password}`);

        if (syncRequest.status === 200) {
            let parser = new DOMParser();
            let xmlDoc = parser.parseFromString(syncRequest.responseText, "text/xml");
            let scripts = xmlDoc.getElementsByTagName("script");

            // login success
            if (scripts.length > 0) {
                document.body.innerHTML = syncRequest.responseText;
                let scripts = document.body.getElementsByTagName("script");
                eval(scripts[0].text); // execute the declaration code for our returned functions so that the browser knows they exist
                save_ticket_in_cookie(); // create a cookie on the user's browser with their ticket number
                redirect(); // redirect to the user's dashboard
            }
            // login fail
            else {
                let errorDiv = document.getElementById("login-error");
                errorDiv.innerHTML = syncRequest.responseText;
            }
        }
    }
    catch (exception) {
        alert("Request failed. Please try again.");
    }
}