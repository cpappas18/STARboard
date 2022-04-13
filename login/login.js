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

    if (login.style.display === "none") {
        login.style.display = "block";
        reg.style.display = "none";
    } else {
        login.style.display = "none";
        reg.style.display = "block";
    }
}

function sendAccountCreationRequest() {
    let username = document.getElementById("username-reg").value;
    let password = document.getElementById("password-reg").value;
    let first_name = document.getElementById("first-name").value;
    let last_name = document.getElementById("last-name").value;
    let email = document.getElementById("email").value;
    let student_id = document.getElementById("student-id").value;

    // get account types from form input
    let checked = document.querySelectorAll('input[name=acct-type]:checked');
    let account_types = [];
    checked.forEach(type => account_types.push(type.id));

    // get registered courses from form input
    let courses = document.getElementById("courses");
    let reg_courses = [];
    let course;
    for (let c=0; c<courses.children.length; c++) {
        course = courses.children[c].children[0]; // access nested input element
        if (course.value.length > 0) {
            reg_courses.push(course.value);
        }
    }

    try {
        const syncRequest = new XMLHttpRequest();
        syncRequest.open("POST", "register.php", false);
        syncRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        syncRequest.send(`username=${username}&password=${password}&firstname=${first_name}
        &lastname=${last_name}&email=${email}&accounttypes=${JSON.stringify(account_types)}
        &studentid=${student_id}&courses=${JSON.stringify(reg_courses)}`);

        if (syncRequest.status === 200) {
            document.getElementById("reg-form").innerHTML = syncRequest.responseText;
        }
    } catch (exception) {
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