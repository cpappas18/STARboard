
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

function saveEdits() {
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
                document.getElementById("account-info").innerHTML = req.responseText;
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

// on load
let username = document.URL.split("=")[1];
getAccount(username);
