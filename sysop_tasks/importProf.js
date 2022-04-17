function getProfs() {
    try {
        const req = new XMLHttpRequest();
        req.open("GET", "importProfsAndCourses.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(null);
    } catch (exception) {
        alert("Request failed. Please try again.");
    }
}

function toggleTable() {
    let table = document.getElementById("accounts-table");
    let act = document.getElementById("edit-account-info");

    if (table.style.display === "none") {
        table.style.display = "block";
        act.style.display = "none";
    } else {
        table.style.display = "none";
        act.style.display = "block";
    }
}

function populateTable() {
    try{
        const req = new XMLHttpRequest();
        req.open("POST", "displayCoursesAndProfsTable.php", false);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send();
        let response = req.responseText;
        document.getElementById("profsAndCourses_table").innerHTML = response;
        
    }catch (exception)
    {
        alert("Request failed.");
    }
}

//on load get the profs assigned to courses from the csv:
getProfs();
populateTable();